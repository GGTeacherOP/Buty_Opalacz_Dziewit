

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

// Sprawdzenie, czy przekazano ID zamówienia
if (isset($_GET['id'])) {
    $id_zamowienia = $_GET['id'];

    // Pobranie podstawowych informacji o zamówieniu
    $sql_zamowienie = "SELECT id_zamowienia, data_zamowienia, id_klienta FROM zamowienia WHERE id_zamowienia = $id_zamowienia";
    $result_zamowienie = $conn->query($sql_zamowienie);

    if ($result_zamowienie->num_rows == 1) {
        $row_zamowienie = $result_zamowienie->fetch_assoc();
        $data_zamowienia = $row_zamowienie['data_zamowienia'];
        $id_klienta = $row_zamowienie['id_klienta'];

        // Pobranie elementów zamówienia
        $sql_elementy = "SELECT ez.id_elementu_zamowienia, p.nazwa AS nazwa_produktu, ez.ilosc, ez.cena_jednostkowa, ez.rozmiar 
                         FROM elementy_zamowienia ez
                         JOIN produkty p ON ez.id_produktu = p.id_produktu
                         WHERE ez.id_zamowienia = $id_zamowienia";
        $result_elementy = $conn->query($sql_elementy);
    } else {
        echo "Nie znaleziono zamówienia o podanym ID.";
        exit;
    }
} else {
    echo "Nieprawidłowe zapytanie. Brak ID zamówienia.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szczegóły Zamówienia nr <?php echo $id_zamowienia; ?></title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Szczegóły Zamówienia nr <?php echo $id_zamowienia; ?></h2>
            </div>
            <div class="card-body">
                <h4>Informacje o Zamówieniu</h4>
                <p><b>Data Zamówienia:</b> <?php echo $data_zamowienia; ?></p>
                <p><b>ID Klienta:</b> <?php echo $id_klienta; ?></p>

                <h4>Elementy Zamówienia</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Elementu</th>
                            <th>Nazwa Produktu</th>
                            <th>Ilość</th>
                            <th>Cena Jednostkowa</th>
                            <th>Rozmiar</th>
                            <th>Suma</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_elementy->num_rows > 0) {
                            $suma_calkowita = 0;
                            while ($row_element = $result_elementy->fetch_assoc()) {
                                $suma_pozycji = $row_element['ilosc'] * $row_element['cena_jednostkowa'];
                                $suma_calkowita += $suma_pozycji;
                                echo "<tr>";
                                echo "<td>" . $row_element['id_elementu_zamowienia'] . "</td>";
                                echo "<td>" . $row_element['nazwa_produktu'] . "</td>";
                                echo "<td>" . $row_element['ilosc'] . "</td>";
                                echo "<td>" . $row_element['cena_jednostkowa'] . "</td>";
                                echo "<td>" . $row_element['rozmiar'] . "</td>";
                                echo "<td>" . number_format($suma_pozycji, 2) . "</td>";
                                echo "</tr>";
                            }
                            echo "<tr><td colspan='5' class='text-end'><b>Suma Całkowita:</b></td><td><b>" . number_format($suma_calkowita, 2) . "</b></td></tr>";
                        } else {
                            echo "<tr><td colspan='6'>Brak elementów w tym zamówieniu.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="zamowienia_szef.php" class="btn btn-secondary">Wróć do Panelu</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>