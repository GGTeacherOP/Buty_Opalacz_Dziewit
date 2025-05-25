<?php
// Rozpoczęcie sesji, umożliwiającej dostęp do zmiennych sesyjnych
session_start();

// Załączenie pliku z funkcjami uwierzytelniającymi
include 'auth_utils.php';
// Sprawdzenie, czy użytkownik jest zalogowany poprzez istnienie zmiennej sesyjnej 'username'
$zalogowany = isset($_SESSION['username']);
// Pobranie roli użytkownika z sesji
$rola = $_SESSION['rola'];

// Utworzenie nowego połączenia z bazą danych MySQL
$conn = new mysqli("localhost", "root", "", "buty");
// Sprawdzenie, czy wystąpił błąd podczas połączenia z bazą danych
if ($conn->connect_error) {
    // Jeśli połączenie nie powiodło się, wyświetlenie komunikatu o błędzie i zakończenie skryptu
    die("Błąd połączenia: " . $conn->connect_error);
}

// Zapytanie SQL do pobrania danych z widoku 'widok_zamowienia_z_klientami'
// Wyniki są sortowane malejąco według daty zamówienia
$sql = "SELECT * FROM widok_zamowienia_z_klientami ORDER BY data_zamowienia DESC";
// Wykonanie zapytania SQL
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zamówienia klientów</title>
    <style>
        /* Resetowanie domyślnych marginesów i paddingów oraz ustawienie box-sizing */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Ustawienie wysokości strony i stylu tła, tekstu i czcionki */
        html, body {
            height: 100%;
            background-color: #121212; /* Ciemne tło */
            color: #f0f0f0; /* Jasny tekst */
            font-family: Arial, sans-serif;
            padding: 20px; /* Dopełnienie wokół treści */
        }

        /* Styl nagłówka strony */
        .header {
            text-align: center; /* Wyśrodkowanie tekstu */
            margin-bottom: 30px; /* Dolny margines */
        }

        /* Styl elementu span w nagłówku (np. dla wyróżnienia nazwy użytkownika) */
        .header span {
            font-size: 20px; /* Rozmiar czcionki */
            color: #00aced; /* Jasnoniebieski kolor */
            font-weight: bold; /* Pogrubienie */
        }

        /* Styl głównego nagłówka h1 */
        h1 {
            text-align: center; /* Wyśrodkowanie tekstu */
            color:#00aced; /* Jasnoniebieski kolor */
            margin-bottom: 20px; /* Dolny margines */
        }

        /* Styl tabeli */
        table {
            width: 100%; /* Szerokość 100% kontenera */
            border-collapse: collapse; /* Scalenie obramowań komórek */
            margin-top: 20px; /* Górny margines */
        }

        /* Styl komórek nagłówka (th) i danych (td) tabeli */
        th, td {
            border: 1px solid #333; /* Ciemnoszare obramowanie */
            padding: 10px; /* Wewnętrzne dopełnienie */
            text-align: left; /* Wyrównanie tekstu do lewej */
        }

        /* Styl komórek nagłówka (th) */
        th {
            background-color: #1f1f1f; /* Ciemniejsze tło nagłówka */
            color: #00aced; /* Jasnoniebieski kolor tekstu nagłówka */
        }

        /* Styl parzystych wierszy tabeli */
        tr:nth-child(even) {
            background-color: #1a1a1a; /* Nieco jaśniejsze ciemne tło */
        }

        /* Styl nieparzystych wierszy tabeli */
        tr:nth-child(odd) {
            background-color: #222; /* Ciemne tło */
        }

        /* Styl nagłówka h2 */
        h2{
            color:#f0f0f0; /* Jasny kolor tekstu */
            text-align:center; /* Wyśrodkowanie tekstu */
            margin-bottom:20px; /* Dolny margines */
        }

        /* Styl przycisku powrotu */
        .back-button {
            display: block; /* Element blokowy, zajmuje całą dostępną szerokość */
            width: 200px; /* Ustawiona szerokość */
            margin: 30px auto; /* Górny i dolny margines, auto dla wyśrodkowania */
            padding: 10px; /* Wewnętrzne dopełnienie */
            background-color: #007bff; /* Niebieskie tło */
            color: white; /* Biały tekst */
            text-align: center; /* Wyśrodkowanie tekstu */
            text-decoration: none; /* Usunięcie podkreślenia linku */
            border-radius: 5px; /* Zaokrąglone rogi */
            transition: background-color 0.3s; /* Płynne przejście koloru tła */
        }

        /* Styl przycisku powrotu po najechaniu kursorem */
        .back-button:hover {
            background-color: #0056b3; /* Ciemniejszy niebieski kolor tła */
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Witaj, <?= htmlspecialchars($_SESSION['username']) ?>! (<?= $rola ?>)</h1>
</div>

<h2>Lista Zamówien</h2>

<?php
// Sprawdzenie, czy zapytanie zwróciło jakieś wiersze
if ($result->num_rows > 0) {
    // Wyświetlenie początku tabeli
    echo "<table>";
    echo "<tr><th>ID Zamówienia</th><th>Data zamówienia</th><th>Kwota całkowita</th><th>Klient</th><th>Email</th></tr>";
    // Iteracja po każdym wierszu wynikowym
    while ($row = $result->fetch_assoc()) {
        // Wyświetlenie wiersza tabeli z danymi zamówienia
        echo "<tr>
                <td>{$row['id_zamowienia']}</td>
                <td>{$row['data_zamowienia']}</td>
                <td>{$row['kwota_calkowita']} zł</td>
                <td>{$row['klient']}</td>
                <td>{$row['email']}</td>
            </tr>";
    }
    // Wyświetlenie końca tabeli
    echo "</table>";
} else {
    // Jeśli nie ma żadnych zamówień, wyświetlenie odpowiedniego komunikatu
    echo "<p>Brak zamówień w bazie.</p>";
}
// Zamknięcie połączenia z bazą danych
$conn->close();
?>

<a href="panel_pracownikow.php" class="back-button">Powrót do panelu</a>

</body>
</html>