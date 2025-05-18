<?php
session_start();
include 'auth_utils.php';

if (!czy_ma_role(['szef'])) {
    header("Location: index.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "buty");
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Funkcja do wyświetlania komunikatów
function wyswietl_komunikat() {
    if (isset($_SESSION['komunikat'])) {
        echo '<div class="alert alert-success">' . $_SESSION['komunikat'] . '</div>';
        unset($_SESSION['komunikat']);
    }
    if (isset($_SESSION['blad'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['blad'] . '</div>';
        unset($_SESSION['blad']);
    }
}

// Obsługa dodawania wydatku
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dodaj_wydatek'])) {
    $data_wydatku = $_POST['data_wydatku'];
    $kategoria_wydatku = $_POST['kategoria_wydatku'];
    $opis_wydatku = $_POST['opis_wydatku'];
    $kwota_wydatku = $_POST['kwota_wydatku'];

    $sql = "INSERT INTO wydatki (data_wydatku, kategoria_wydatku, opis_wydatku, kwota_wydatku) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssd", $data_wydatku, $kategoria_wydatku, $opis_wydatku, $kwota_wydatku);

    if ($stmt->execute()) {
        $_SESSION['komunikat'] = "Wydatek dodany pomyślnie.";
    } else {
        $_SESSION['blad'] = "Błąd podczas dodawania wydatku: " . $stmt->error;
        error_log("Błąd dodawania wydatku: " . $stmt->error, 0); // Logowanie błędu
    }
    $stmt->close();
    header("Location: wydatki.php"); // Przekierowanie, aby odświeżyć listę
    exit;
}

// Obsługa usuwania wydatku
if (isset($_GET['usun_wydatek'])) {
    $id_wydatku = $_GET['usun_wydatek'];

    $sql = "DELETE FROM wydatki WHERE id_wydatku = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_wydatku);

    if ($stmt->execute()) {
        $_SESSION['komunikat'] = "Wydatek usunięty pomyślnie.";
    } else {
        $_SESSION['blad'] = "Błąd podczas usuwania wydatku: " . $stmt->error;
        error_log("Błąd usuwania wydatku: " . $stmt->error, 0); // Logowanie błędu
    }
    $stmt->close();
    header("Location: wydatki.php"); // Przekierowanie
    exit;
}

// Pobranie listy wydatków
$sql = "SELECT id_wydatku, data_wydatku, kategoria_wydatku, opis_wydatku, kwota_wydatku FROM wydatki ORDER BY data_wydatku DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Szefa - Wydatki</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 1rem 1rem 0 0;
            padding: 1rem;
        }
        .card-body {
            padding: 1.5rem;
        }
        .table {
            margin-top: 20px;
        }
        .btn-sm {
            margin-right: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Zarządzaj Wydatkami</h2>
                <p>Dodawaj, usuwaj i przeglądaj wydatki sklepu.</p>
            </div>
            <div class="card-body">
                <?php wyswietl_komunikat(); ?>
                <h4>Dodaj Nowy Wydatek</h4>
                <form method="post" action="wydatki.php">
                    <div class="mb-3">
                        <label for="data_wydatku" class="form-label">Data Wydatku:</label>
                        <input type="date" class="form-control" id="data_wydatku" name="data_wydatku" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategoria_wydatku" class="form-label">Kategoria:</label>
                        <input type="text" class="form-control" id="kategoria_wydatku" name="kategoria_wydatku" required>
                    </div>
                    <div class="mb-3">
                        <label for="opis_wydatku" class="form-label">Opis:</label>
                        <input type="text" class="form-control" id="opis_wydatku" name="opis_wydatku" required>
                    </div>
                    <div class="mb-3">
                        <label for="kwota_wydatku" class="form-label">Kwota:</label>
                        <input type="number" class="form-control" id="kwota_wydatku" name="kwota_wydatku" step="0.01" required>
                    </div>
                    <button type="submit" name="dodaj_wydatek" class="btn btn-primary">Dodaj Wydatek</button>
                </form>

                <h4>Lista Wydatków</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Kategoria</th>
                            <th>Opis</th>
                            <th>Kwota</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id_wydatku"] . "</td>";
                                echo "<td>" . $row["data_wydatku"] . "</td>";
                                echo "<td>" . $row["kategoria_wydatku"] . "</td>";
                                echo "<td>" . $row["opis_wydatku"] . "</td>";
                                echo "<td>" . $row["kwota_wydatku"] . "</td>";
                                echo "<td>
                                        <a href='wydatki.php?usun_wydatek=" . $row["id_wydatku"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Czy na pewno chcesz usunąć ten wydatek?\");'>Usuń</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Brak wydatków w bazie danych.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="panel_szefa.php" class="btn btn-secondary">Wróć do Panelu Szefa</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>