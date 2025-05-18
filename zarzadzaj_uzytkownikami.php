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

$sql = "SELECT id_klienta, nazwa_uzytkownika, email, data_rejestracji, rola FROM klienci";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora - Klienci</title>
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
                <h2>Zarządzaj Klientami</h2>
                <p>Przeglądaj i zarządzaj użytkownikami sklepu.</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nazwa Użytkownika</th>
                                <th>Email</th>
                                <th>Data Rejestracji</th>
                                <th>Rola</th>
                                <th>Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["id_klienta"] . "</td>";
                                    echo "<td>" . $row["nazwa_uzytkownika"] . "</td>";
                                    echo "<td>" . $row["email"] . "</td>";
                                    echo "<td>" . $row["data_rejestracji"] . "</td>";
                                    echo "<td>" . $row["rola"] . "</td>";
                                    echo "<td>
                                            <a href='edytuj_uzytkownika_admin.php?id=" . $row["id_klienta"] . "' class='btn btn-primary btn-sm'>Edytuj</a>
                                            <a href='usun_uzytkownika_admin.php?id=" . $row["id_klienta"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Czy na pewno chcesz usunąć tego użytkownika?\");'>Usuń</a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>Brak użytkowników w bazie danych.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <a href="panel_admina.php" class="btn btn-secondary">Wróć do Panelu Admina</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>