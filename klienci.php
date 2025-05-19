<?php
// Rozpoczęcie sesji PHP, umożliwiającej dostęp do zmiennych sesyjnych.
session_start();
// Dołączenie pliku 'auth_utils.php', który prawdopodobnie zawiera funkcje autoryzacji, takie jak 'czy_ma_role'.
include 'auth_utils.php';

// Sprawdzenie, czy zalogowany użytkownik posiada jedną z wymaganych ról: 'admin' lub 'szef'.
// Funkcja 'czy_ma_role' (z pliku 'auth_utils.php') zwraca true, jeśli użytkownik ma przypisaną jedną z tych ról.
if (!czy_ma_role(['admin', 'szef'])) {
    // Jeśli użytkownik nie ma odpowiednich uprawnień, następuje przekierowanie na stronę główną (index.php).
    header("Location: index.php");
    // Zakończenie wykonywania bieżącego skryptu po przekierowaniu.
    exit;
}

// Utworzenie nowego połączenia z bazą danych MySQL.
// Parametry połączenia to: host ('localhost'), użytkownik ('root'), hasło (puste w tym przypadku), nazwa bazy danych ('buty').
$conn = new mysqli("localhost", "root", "", "buty");
// Sprawdzenie, czy wystąpił błąd podczas łączenia z bazą danych.
if ($conn->connect_error) {
    // Jeśli połączenie nie powiodło się, wyświetlenie komunikatu o błędzie i zakończenie skryptu.
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Zapytanie SQL do pobrania listy wszystkich klientów z tabeli 'klienci'.
// Wybierane są kolumny: 'id_klienta', 'nazwa_uzytkownika', 'email', 'data_rejestracji', 'rola'.
$sql = "SELECT id_klienta, nazwa_uzytkownika, email, data_rejestracji, rola FROM klienci";
// Wykonanie zapytania SQL na połączeniu z bazą danych i zapisanie wyniku w zmiennej '$result'.
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
            background-color: #f8f9fa; /* Jasnoszare tło strony */
        }
        .container {
            margin-top: 50px; /* Górny margines kontenera, oddalający go od góry okna. */
        }
        .card {
            border-radius: 1rem; /* Zaokrąglone rogi kontenera z klientami. */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Delikatny cień pod kontenerem. */
        }
        .card-header {
            background-color: #007bff; /* Niebieskie tło nagłówka. */
            color: white; /* Biały tekst w nagłówku. */
            text-align: center; /* Wyśrodkowanie tekstu w nagłówku. */
            border-radius: 1rem 1rem 0 0; /* Zaokrąglone górne rogi nagłówka. */
            padding: 1rem; /* Wewnętrzny odstęp w nagłówku. */
        }
        .card-body {
            padding: 1.5rem; /* Wewnętrzny odstęp w ciele kontenera. */
        }
        .table {
            margin-top: 20px; /* Górny margines tabeli z klientami. */
        }
        .btn-sm {
            margin-right: 5px; /* Mały prawy margines dla przycisków akcji. */
        }
        .btn-primary {
            background-color: #007bff; /* Niebieskie tło domyślnego przycisku. */
            border-color: #007bff; /* Niebieska ramka domyślnego przycisku. */
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Ciemniejszy niebieski kolor tła przy najechaniu kursorem. */
            border-color: #0056b3; /* Ciemniejsza niebieska ramka przy najechaniu kursorem. */
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
                            // Sprawdzenie, czy zapytanie do bazy danych zwróciło jakieś wiersze (klientów).
                            if ($result->num_rows > 0) {
                                // Jeśli są jacyś klienci, iteruj po każdym wierszu wyniku.
                                while ($row = $result->fetch_assoc()) {
                                    // Dla każdego klienta utwórz wiersz tabeli (<tr>).
                                    echo "<tr>";
                                    // Wyświetl ID klienta (<td> - komórka tabeli).
                                    echo "<td>" . $row["id_klienta"] . "</td>";
                                    // Wyświetl nazwę użytkownika klienta.
                                    echo "<td>" . $row["nazwa_uzytkownika"] . "</td>";
                                    // Wyświetl adres email klienta.
                                    echo "<td>" . $row["email"] . "</td>";
                                    // Wyświetl datę rejestracji klienta.
                                    echo "<td>" . $row["data_rejestracji"] . "</td>";
                                    // Wyświetl rolę klienta.
                                    echo "<td>" . $row["rola"] . "</td>";
                                    // Kolumna z przyciskami akcji (Edytuj i Usuń).
                                    echo "<td>
                                            <a href='edytuj_uzytkownika_szef.php?id=" . $row["id_klienta"] . "' class='btn btn-primary btn-sm'>Edytuj</a>
                                            <a href='usun_uzytkownika_szef.php?id=" . $row["id_klienta"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Czy na pewno chcesz usunąć tego użytkownika?\");'>Usuń</a>
                                        </td>";
                                    // Koniec wiersza tabeli.
                                    echo "</tr>";
                                }
                            } else {
                                // Jeśli nie ma żadnych klientów w bazie danych, wyświetl odpowiedni komunikat w jednej kolumnie rozciągniętej na całą szerokość tabeli.
                                echo "<tr><td colspan='6'>Brak użytkowników w bazie danych.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <a href="panel_szefa.php" class="btn btn-secondary">Wróć do Panelu Szefa</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Zamknięcie połączenia z bazą danych, aby zwolnić zasoby.
$conn->close();
?>