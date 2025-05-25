<?php
session_start(); // Rozpoczęcie sesji PHP, umożliwiającej dostęp do zmiennych sesyjnych.

include 'auth_utils.php'; // Dołączenie pliku z funkcjami autoryzacji (np. sprawdzanie roli).
$zalogowany = isset($_SESSION['username']); // Sprawdzenie, czy zmienna sesyjna 'username' jest ustawiona, co oznacza, że użytkownik jest zalogowany.
$rola = $_SESSION['rola']; // Pobranie roli użytkownika z sesji.

// Nawiązanie połączenia z bazą danych MySQL.
$conn = new mysqli("localhost", "root", "", "buty");
// Sprawdzenie, czy wystąpił błąd podczas łączenia z bazą danych.
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error); // Wyświetlenie komunikatu o błędzie i zatrzymanie skryptu.
}

// Zapytanie SQL do pobrania nazwy, marki, kategorii i ceny wszystkich produktów z tabeli 'produkty', posortowanych alfabetycznie według nazwy.
$sql = "SELECT nazwa, marka, kategoria, cena FROM produkty ORDER BY nazwa ASC";
$result = $conn->query($sql); // Wykonanie zapytania SQL i zapisanie wyniku w zmiennej $result.
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Panel pracownika - Produkty</title>
    <style>
        /* Style CSS dla wyglądu strony */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            background-color: #121212; /* Ciemne tło strony */
            color: #f0f0f0; /* Jasny kolor tekstu */
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .header {
            text-align: center; /* Wyśrodkowanie tekstu w nagłówku */
            margin-bottom: 30px; /* Dolny margines nagłówka */
        }

        .header span {
            font-size: 20px; /* Większy rozmiar czcionki dla elementu span */
            color: #00aced; /* Jasnoniebieski kolor tekstu */
            font-weight: bold; /* Pogrubienie tekstu */
        }

        h1 {
            text-align: center; /* Wyśrodkowanie nagłówka */
            color:#00aced; /* Jasnoniebieski kolor nagłówka */
            margin-bottom: 20px; /* Dolny margines nagłówka */
        }

        table {
            width: 100%; /* Szerokość tabeli równa szerokości kontenera */
            border-collapse: collapse; /* Scalenie krawędzi komórek tabeli */
            margin-top: 20px; /* Górny margines tabeli */
        }

        th, td {
            border: 1px solid #333; /* Ciemnoszare obramowanie komórek tabeli */
            padding: 10px; /* Wewnętrzny odstęp w komórkach tabeli */
            text-align: left; /* Wyrównanie tekstu do lewej w komórkach tabeli */
        }

        th {
            background-color: #1f1f1f; /* Ciemniejsze tło nagłówka tabeli */
            color: #00aced; /* Jasnoniebieski kolor tekstu w nagłówku tabeli */
        }

        tr:nth-child(even) {
            background-color: #1a1a1a; /* Jeszcze ciemniejsze tło dla parzystych wierszy */
        }

        tr:nth-child(odd) {
            background-color: #222; /* Nieco jaśniejsze tło dla nieparzystych wierszy */
        }
        h2{
            color:#f0f0f0; /* Jasny kolor tekstu dla nagłówka h2 */
            text-align:center; /* Wyśrodkowanie nagłówka h2 */
            margin-bottom:20px; /* Dolny margines nagłówka h2 */
        }

        .back-button {
            display: block; /* Element blokowy, zajmuje całą dostępną szerokość */
            width: 200px; /* Ustawienie stałej szerokości przycisku */
            margin: 30px auto; /* Górny i dolny margines, auto dla wyśrodkowania */
            padding: 10px; /* Wewnętrzny odstęp w przycisku */
            background-color: #007bff; /* Niebieskie tło przycisku */
            color: white; /* Biały kolor tekstu przycisku */
            text-align: center; /* Wyśrodkowanie tekstu w przycisku */
            text-decoration: none; /* Usunięcie domyślnego podkreślenia linku */
            border-radius: 5px; /* Zaokrąglone rogi przycisku */
            transition: background-color 0.3s; /* Płynne przejście koloru tła */
        }

        .back-button:hover {
            background-color: #0056b3; /* Ciemniejszy niebieski kolor tła po najechaniu */
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Witaj, <?= htmlspecialchars($_SESSION['username']) ?>! (<?= $rola ?>)</h1>
</div>

<h2>Lista produktów</h2>

<?php
// Sprawdzenie, czy zapytanie SQL zostało wykonane pomyślnie i czy zwróciło więcej niż 0 wierszy (produkty).
if ($result && $result->num_rows > 0) {
    echo "<table>"; // Otwarcie tabeli HTML.
    echo "<tr><th>Nazwa</th><th>Marka</th><th>Kategoria</th><th>Cena</th></tr>"; // Wyświetlenie wiersza nagłówkowego tabeli.
    // Pętla while iterująca po każdym wierszu (każdym produkcie) zwróconym przez zapytanie.
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                    <td>{$row['nazwa']}</td>
                    <td>{$row['marka']}</td>
                    <td>{$row['kategoria']}</td>
                    <td>{$row['cena']} zł</td>
                  </tr>"; // Wyświetlenie danych każdego produktu w nowym wierszu tabeli.
    }
    echo "</table>"; // Zamknięcie tabeli HTML.
} else {
    echo "<p>Brak produktów w bazie.</p>"; // Wyświetlenie komunikatu, jeśli nie ma żadnych produktów w bazie danych.
}
$conn->close(); // Zamknięcie połączenia z bazą danych.
?>

<a href="panel_pracownikow.php" class="back-button">Powrót do panelu</a>

</body>
</html>