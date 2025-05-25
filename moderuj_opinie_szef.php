<?php
session_start(); // Rozpoczęcie sesji PHP, aby móc korzystać ze zmiennych sesyjnych.
include 'auth_utils.php'; // Dołączenie pliku z funkcjami autoryzacji, w tym 'czy_ma_role()'.

// Sprawdzenie, czy zalogowany użytkownik ma rolę 'admin' lub 'szef'.
if (!czy_ma_role(['admin', 'szef'])) {
    header("Location: index.php"); // Jeśli nie ma odpowiedniej roli, przekieruj na stronę główną.
    exit; // Zakończ wykonywanie skryptu po przekierowaniu.
}

// Nawiązanie połączenia z bazą danych MySQL.
$conn = new mysqli("localhost", "root", "", "buty");
// Sprawdzenie, czy wystąpił błąd podczas łączenia z bazą danych.
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error); // Wyświetlenie błędu i zatrzymanie skryptu.
}

// Obsługa usuwania opinii po naciśnięciu przycisku "Usuń".
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usun_opinie'])) {
    $id_opinii = $_POST['id_opinii']; // Pobranie ID opinii do usunięcia z formularza.

    // Zapytanie SQL usuwające opinię z tabeli 'opinie' na podstawie jej ID.
    $sql = "DELETE FROM opinie WHERE id_opinii = $id_opinii";
    // Wykonanie zapytania SQL.
    if ($conn->query($sql) === TRUE) {
        // Jeśli usunięcie się powiodło, ustaw komunikat w sesji.
        $_SESSION['komunikat'] = "Opinia została usunięta.";
        header("Location: moderuj_opinie_szef.php"); // Przekieruj z powrotem na stronę moderowania opinii.
        exit; // Zakończ wykonywanie skryptu po przekierowaniu.
    } else {
        // Jeśli wystąpił błąd podczas usuwania, zapisz komunikat o błędzie.
        $error = "Błąd usuwania opinii: " . $conn->error;
    }
}

// Zapytanie SQL pobierające listę wszystkich opinii wraz z nazwą produktu.
$sql = "SELECT o.id_opinii, o.komentarz, o.data_opinii, o.imie, p.nazwa AS nazwa_produktu
        FROM opinie o
        JOIN produkty p ON o.id_produktu = p.id_produktu
        ORDER BY o.data_opinii DESC"; // Sortowanie opinii od najnowszej.
$result = $conn->query($sql); // Wykonanie zapytania.

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora - Moderuj Opinie</title>
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
            margin-bottom: 5px;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Moderuj Opinie</h2>
                <p>Lista opinii klientów o produktach.</p>
            </div>
            <div class="card-body">

                <?php if (isset($_SESSION['komunikat'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['komunikat'] ?></div>
                    <?php unset($_SESSION['komunikat']); // Usunięcie komunikatu z sesji po wyświetleniu ?>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Opinii</th>
                            <th>Produkt</th>
                            <th>Autor</th>
                            <th>Treść</th>
                            <th>Data Dodania</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sprawdzenie, czy pobrano jakieś opinie z bazy danych.
                        if ($result->num_rows > 0) {
                            // Iteracja po każdej opinii.
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id_opinii"] . "</td>";
                                echo "<td>" . $row["nazwa_produktu"] . "</td>";
                                echo "<td>" . $row["imie"] . "</td>"; // Wyświetlenie imienia autora opinii.
                                echo "<td>" . $row["komentarz"] . "</td>";  // Wyświetlenie treści komentarza.
                                echo "<td>" . $row["data_opinii"] . "</td>";
                                echo "<td>
                                        <form method='post' style='display:inline-block;'>
                                            <input type='hidden' name='id_opinii' value='" . $row["id_opinii"] . "'>
                                            <button type='submit' name='usun_opinie' class='btn btn-danger btn-sm' onclick=\"return confirm('Czy na pewno chcesz usunąć tę opinię?')\">Usuń</button>
                                        </form>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Brak opinii do wyświetlenia.</td></tr>";
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
$conn->close(); // Zamknięcie połączenia z bazą danych.
?>