<?php
session_start(); // Rozpoczęcie lub wznowienie sesji, umożliwiające dostęp do zmiennych sesyjnych.
include 'auth_utils.php'; // Dołączenie pliku zawierającego funkcje pomocnicze do autentykacji i sprawdzania ról.

// Sprawdzenie, czy zalogowany użytkownik posiada jedną z wymaganych ról ('admin' lub 'szef').
// Funkcja 'czy_ma_role' (z pliku 'auth_utils.php') zwraca true, jeśli użytkownik ma przypisaną jedną z podanych ról.
if (!czy_ma_role(['admin', 'szef'])) {
    // Jeśli użytkownik nie ma odpowiednich uprawnień, zostanie przekierowany na stronę główną ('index.php').
    header("Location: index.php");
    exit; // Zakończenie wykonywania skryptu po przekierowaniu, aby uniemożliwić dalsze działanie nieautoryzowanemu użytkownikowi.
}

// Nawiązanie połączenia z bazą danych MySQL.
// Parametry połączenia: adres serwera ('localhost'), nazwa użytkownika ('root'), hasło (puste w tym przypadku), nazwa bazy danych ('buty').
$conn = new mysqli("localhost", "root", "", "buty");
// Sprawdzenie, czy wystąpił błąd podczas łączenia z bazą danych.
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error); // Wyświetlenie komunikatu o błędzie połączenia i zatrzymanie dalszego wykonywania skryptu.
}

// Przygotowanie zapytania SQL do pobrania danych wszystkich klientów z tabeli 'klienci'.
// Wybierane są kolumny: 'id_klienta', 'nazwa_uzytkownika', 'email', 'data_rejestracji' oraz 'rola'.
$sql = "SELECT id_klienta, nazwa_uzytkownika, email, data_rejestracji, rola FROM klienci";
// Wykonanie zapytania SQL na połączonej bazie danych i zapisanie wyniku w zmiennej '$result'.
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
            background-color: #f8f9fa; // Ustawienie jasnoszarego tła dla całej strony.
        }
        .container {
            margin-top: 50px; // Dodanie górnego marginesu do kontenera, aby odsunąć go od górnej krawędzi okna.
        }
        .card {
            border-radius: 1rem; // Zaokrąglenie rogów elementu karty.
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); // Dodanie subtelnego cienia pod kartą dla efektu głębi.
        }
        .card-header {
            background-color: #007bff; // Ustawienie niebieskiego tła dla nagłówka karty (kolor primary z Bootstrap).
            color: white; // Ustawienie białego koloru tekstu w nagłówku.
            text-align: center; // Wyśrodkowanie tekstu w nagłówku.
            border-radius: 1rem 1rem 0 0; // Zaokrąglenie tylko górnych rogów nagłówka.
            padding: 1rem; // Dodanie wewnętrznego odstępu w nagłówku.
        }
        .card-body {
            padding: 1.5rem; // Dodanie większego wewnętrznego odstępu w ciele karty.
        }
        .table {
            margin-top: 20px; // Dodanie górnego marginesu do tabeli.
        }
        .btn-sm {
            margin-right: 5px; // Dodanie prawego marginesu do małych przycisków, aby je odseparować.
        }
        .btn-primary {
            background-color: #007bff; // Ustawienie niebieskiego tła dla przycisku primary.
            border-color: #007bff; // Ustawienie niebieskiego koloru obramowania przycisku primary.
        }
        .btn-primary:hover {
            background-color: #0056b3; // Ciemniejszy niebieski kolor tła przy najechaniu kursorem na przycisk primary.
            border-color: #0056b3; // Ciemniejszy niebieski kolor obramowania przy najechaniu kursorem na przycisk primary.
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
                            // Sprawdzenie, czy zapytanie zwróciło jakieś wiersze (czy są jacyś klienci w bazie danych).
                            if ($result->num_rows > 0) {
                                // Jeśli tak, iteracja po każdym wierszu wyniku zapytania.
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["id_klienta"] . "</td>"; // Wyświetlenie ID klienta.
                                    echo "<td>" . $row["nazwa_uzytkownika"] . "</td>"; // Wyświetlenie nazwy użytkownika.
                                    echo "<td>" . $row["email"] . "</td>"; // Wyświetlenie adresu email klienta.
                                    echo "<td>" . $row["data_rejestracji"] . "</td>"; // Wyświetlenie daty rejestracji klienta.
                                    echo "<td>" . $row["rola"] . "</td>"; // Wyświetlenie roli klienta.
                                    echo "<td>
                                            <a href='edytuj_uzytkownika_admin.php?id=" . $row["id_klienta"] . "' class='btn btn-primary btn-sm'>Edytuj</a>
                                            <a href='usun_uzytkownika_admin.php?id=" . $row["id_klienta"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Czy na pewno chcesz usunąć tego użytkownika?\");'>Usuń</a>
                                        </td>"; // Wyświetlenie przycisków do edycji i usunięcia użytkownika. Przycisk Usuń ma dodatkowe potwierdzenie JavaScript.
                                    echo "</tr>";
                                }
                            } else {
                                // Jeśli nie ma żadnych klientów w bazie danych, wyświetlenie wiersza z informacją.
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
// Zamknięcie połączenia z bazą danych, aby zwolnić zasoby serwera.
$conn->close();
?>