<?php
session_start();

include 'auth_utils.php';
$zalogowany = isset($_SESSION['username']);
$rola = $_SESSION['rola'];

$conn = new mysqli("localhost", "root", "", "buty");
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Pobierz dane z widoku
$sql = "SELECT * FROM widok_zamowienia_z_klientami ORDER BY data_zamowienia DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zamówienia klientów</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            background-color: #121212;
            color: #f0f0f0;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header span {
            font-size: 20px;
            color: #00aced;
            font-weight: bold;
        }

        h1 {
            text-align: center;
            color:#00aced;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #333;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #1f1f1f;
            color: #00aced;
        }

        tr:nth-child(even) {
            background-color: #1a1a1a;
        }

        tr:nth-child(odd) {
            background-color: #222;
        }
        h2{
            color:#f0f0f0;
            text-align:center;
            margin-bottom:20px;
        }
        
        .back-button {
            display: block;
            width: 200px;
            margin: 30px auto;
            padding: 10px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Witaj, <?= htmlspecialchars($_SESSION['username']) ?>! (<?= $rola ?>)</h1>
</div>

<h2>Lista Zamówien</h2>

<?php
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID Zamówienia</th><th>Data zamówienia</th><th>Kwota całkowita</th><th>Klient</th><th>Email</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id_zamowienia']}</td>
            <td>{$row['data_zamowienia']}</td>
            <td>{$row['kwota_calkowita']} zł</td>
            <td>{$row['klient']}</td>
            <td>{$row['email']}</td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Brak zamówień w bazie.</p>";
}
$conn->close();
?>

<a href="panel_pracownikow.php" class="back-button">Powrót do panelu</a>

</body>
</html>