<?php
session_start();
include 'auth_utils.php';

if (!czy_ma_role(['admin', 'szef'])) {
    header("Location: index.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "buty");
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Pobierz listę pracowników
$sql = "SELECT id_pracownika, nazwa_uzytkownika, email, stanowisko, pensja FROM pracownicy";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora - Zarządzaj Pracownikami</title>
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
                <h2>Zarządzaj Pracownikami</h2>
                <p>Lista pracowników i opcje zarządzania.</p>
            </div>
            <div class="card-body">

                <?php if (isset($_SESSION['komunikat'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['komunikat'] ?></div>
                    <?php unset($_SESSION['komunikat']); ?>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nazwa Użytkownika</th>
                            <th>Email</th>
                            <th>Stanowisko</th>
                            <th>Pensja</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id_pracownika"] . "</td>";
                                echo "<td>" . $row["nazwa_uzytkownika"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>" . $row["stanowisko"] . "</td>";
                                echo "<td>" . $row["pensja"] . "</td>";
                                echo "<td>
                                        <a href='edytuj_pracownika_szef.php?id=" . $row["id_pracownika"] . "' class='btn btn-primary btn-sm'>Edytuj</a>
                                        <form method='post' action='usun_pracownika_szef.php' style='display:inline-block;'>
                                            <input type='hidden' name='id_pracownika' value='" . $row["id_pracownika"] . "'>
                                            <button type='submit' class='btn btn-danger btn-sm' onclick=\"return confirm('Czy na pewno chcesz usunąć tego pracownika?')\">Usuń</button>
                                        </form>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Brak pracowników w bazie danych.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="dodaj_pracownika_szef.php" class="btn btn-success">Dodaj Pracownika</a>
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