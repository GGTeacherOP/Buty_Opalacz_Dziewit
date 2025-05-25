<?php
session_start();
include 'auth_utils.php';

if (!czy_ma_role(['kierownik', 'admin', 'szef'])) {
    header("Location: index.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "buty");
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

$sql = "SELECT id_pracownika, nazwa_uzytkownika, email, stanowisko, pensja FROM pracownicy ORDER BY nazwa_uzytkownika ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Kierownika - Pracownicy</title>
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
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #1e7e34;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Zarządzaj Pracownikami</h2>
            </div>
            <div class="card-body">
                <a href="dodaj_pracownika.php" class="btn btn-success btn-sm">Dodaj Pracownika</a>
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
                                        <a href='edytuj_pracownika.php?id=" . $row["id_pracownika"] . "' class='btn btn-sm btn-primary'>Edytuj</a>
                                        <a href='usun_pracownika.php?id=" . $row["id_pracownika"] . "' class='btn btn-sm btn-danger'>Usuń</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Brak pracowników w bazie danych.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="panel_kierownika.php" class="btn btn-secondary">Wróć do Panelu Kierownika</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>