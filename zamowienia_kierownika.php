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

// Pobierz dane z widoku
$sql = "SELECT id_zamowienia, data_zamowienia, kwota_calkowita, id_klienta, klient, email FROM widok_zamowienia_z_klientami ORDER BY data_zamowienia DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Kierownika - Zamówienia</title>
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
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn-info:hover {
            background-color: #117a8b;
            border-color: #117a8b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Zarządzaj Zamówieniami</h2>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Zamówienia</th>
                            <th>Data Zamówienia</th>
                            <th>Kwota</th>
                            <th>Id klienta</th>
                            <th>Imie</th>
                            <th>Email</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id_zamowienia"] . "</td>";
                                echo "<td>" . $row["data_zamowienia"] . "</td>";
                                echo "<td>" . $row["kwota_calkowita"] . "</td>";
                                echo "<td>" . $row["id_klienta"] . "</td>";
                                  echo "<td>" . $row["klient"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>
                                      
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Brak zamówień.</td></tr>";
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