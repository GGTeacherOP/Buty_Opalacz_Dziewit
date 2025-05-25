

<?php
// Rozpoczęcie sesji, umożliwiającej dostęp do zmiennych sesyjnych
session_start();
// Załączenie pliku z funkcjami uwierzytelniającymi
include 'auth_utils.php';

// Sprawdzenie, czy użytkownik ma jedną z wymaganych ról (kierownik, admin, szef)
// Funkcja czy_ma_role() prawdopodobnie zwraca true, jeśli rola użytkownika (pobrana z sesji lub bazy danych)
// znajduje się w podanej tablicy ról.
if (!czy_ma_role(['kierownik', 'admin', 'szef'])) {
    // Jeśli użytkownik nie ma wymaganej roli, zostaje przekierowany na stronę index.php
    header("Location: index.php");
    // Zakończenie wykonywania bieżącego skryptu, aby zapobiec dalszemu działaniu
    exit;
}

// Utworzenie nowego połączenia z bazą danych MySQL
$conn = new mysqli("localhost", "root", "", "buty");
// Sprawdzenie, czy wystąpił błąd podczas połączenia z bazą danych
if ($conn->connect_error) {
    // Jeśli połączenie nie powiodło się, wyświetlenie komunikatu o błędzie i zakończenie skryptu
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Sprawdzenie, czy w żądaniu GET przekazano parametr 'id'
if (isset($_GET['id'])) {
    // Pobranie wartości parametru 'id' z żądania GET i przypisanie jej do zmiennej $id_zamowienia
    $id_zamowienia = $_GET['id'];

    // Zapytanie SQL do pobrania podstawowych informacji o zamówieniu o podanym ID
    $sql_zamowienie = "SELECT id_zamowienia, data_zamowienia, id_klienta FROM zamowienia WHERE id_zamowienia = $id_zamowienia";
    // Wykonanie zapytania SQL na połączeniu z bazą danych
    $result_zamowienie = $conn->query($sql_zamowienie);

    // Sprawdzenie, czy zapytanie zwróciło dokładnie jeden wiersz (oczekiwane dla unikalnego ID zamówienia)
    if ($result_zamowienie->num_rows == 1) {
        // Pobranie wiersza wynikowego jako asocjacyjnej tablicy
        $row_zamowienie = $result_zamowienie->fetch_assoc();
        // Przypisanie wartości z tablicy do odpowiednich zmiennych
        $data_zamowienia = $row_zamowienie['data_zamowienia'];
        $id_klienta = $row_zamowienie['id_klienta'];

        // Zapytanie SQL do pobrania elementów (produktów) należących do danego zamówienia
        // Łączy tabelę elementy_zamowienia z tabelą produkty, aby uzyskać nazwę produktu
        $sql_elementy = "SELECT ez.id_elementu_zamowienia, p.nazwa AS nazwa_produktu, ez.ilosc, ez.cena_jednostkowa, ez.rozmiar
                           FROM elementy_zamowienia ez
                           JOIN produkty p ON ez.id_produktu = p.id_produktu
                           WHERE ez.id_zamowienia = $id_zamowienia";
        // Wykonanie zapytania SQL
        $result_elementy = $conn->query($sql_elementy);
    } else {
        // Jeśli nie znaleziono zamówienia o podanym ID, wyświetlenie komunikatu i zakończenie skryptu
        echo "Nie znaleziono zamówienia o podanym ID.";
        exit;
    }
} else {
    // Jeśli nie przekazano ID zamówienia w żądaniu GET, wyświetlenie komunikatu i zakończenie skryptu
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
            background-color: #f8f9fa; /* Jasnoszare tło strony */
        }
        .container {
            margin-top: 50px; /* Górny margines kontenera, aby odsunąć go od góry okna */
        }
        .card {
            border-radius: 1rem; /* Zaokrąglone rogi karty */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Lekki cień pod kartą */
        }
        .card-header {
            background-color: #007bff; /* Niebieskie tło nagłówka karty */
            color: white; /* Biały tekst w nagłówku */
            text-align: center; /* Wyśrodkowanie tekstu w nagłówku */
            border-radius: 1rem 1rem 0 0; /* Zaokrąglenie tylko górnych rogów nagłówka */
            padding: 1rem; /* Wewnętrzny odstęp w nagłówku */
        }
        .card-body {
            padding: 1.5rem; /* Wewnętrzny odstęp w ciele karty */
        }
        .table {
            margin-top: 20px; /* Górny margines tabeli */
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
                        // Sprawdzenie, czy zapytanie o elementy zamówienia zwróciło jakieś wiersze
                        if ($result_elementy->num_rows > 0) {
                            // Inicjalizacja zmiennej do przechowywania sumy całkowitej zamówienia
                            $suma_calkowita = 0;
                            // Pętla przechodząca przez każdy wiersz wynikowy (każdy element zamówienia)
                            while ($row_element = $result_elementy->fetch_assoc()) {
                                // Obliczenie sumy dla bieżącej pozycji (ilość * cena jednostkowa)
                                $suma_pozycji = $row_element['ilosc'] * $row_element['cena_jednostkowa'];
                                // Dodanie sumy pozycji do sumy całkowitej
                                $suma_calkowita += $suma_pozycji;
                                // Wyświetlenie wiersza tabeli z danymi elementu zamówienia
                                echo "<tr>";
                                echo "<td>" . $row_element['id_elementu_zamowienia'] . "</td>";
                                echo "<td>" . $row_element['nazwa_produktu'] . "</td>";
                                echo "<td>" . $row_element['ilosc'] . "</td>";
                                echo "<td>" . $row_element['cena_jednostkowa'] . "</td>";
                                echo "<td>" . $row_element['rozmiar'] . "</td>";
                                // Wyświetlenie sumy pozycji z formatowaniem do dwóch miejsc po przecinku
                                echo "<td>" . number_format($suma_pozycji, 2) . "</td>";
                                echo "</tr>";
                            }
                            // Wyświetlenie wiersza z sumą całkowitą zamówienia
                            echo "<tr><td colspan='5' class='text-end'><b>Suma Całkowita:</b></td><td><b>" . number_format($suma_calkowita, 2) . "</b></td></tr>";
                        } else {
                            // Jeśli nie ma elementów w zamówieniu, wyświetlenie odpowiedniego komunikatu
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
// Zamknięcie połączenia z bazą danych
$conn->close();
?>