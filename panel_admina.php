<?php
session_start(); // Rozpoczęcie sesji PHP, aby móc korzystać ze zmiennych sesyjnych.
include 'auth_utils.php'; // Dołączenie pliku z funkcjami autoryzacji, w tym 'czy_ma_role()'.

// Sprawdzenie, czy zalogowany użytkownik ma rolę 'admin' lub 'szef'.
if (!czy_ma_role(['admin', 'szef'])) {
    header("Location: index.php"); // Jeśli nie ma odpowiedniej roli, przekieruj na stronę główną.
    exit; // Zakończ wykonywanie skryptu po przekierowaniu.
}

// Nawiązanie połączenia z bazą danych MySQL.
$conn = new mysqli("localhost", "root", "", "buty");
// Sprawdzenie, czy wystąpił błąd podczas łączenia z bazą danych.
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error); // Wyświetlenie błędu i zatrzymanie skryptu.
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Jasnoszare tło strony */
        }
        .container {
            margin-top: 50px; /* Górny margines kontenera */
        }
        .card {
            border-radius: 1rem; /* Zaokrąglone rogi karty */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Delikatny cień karty */
        }
        .card-header {
            background-color: #007bff; /* Niebieskie tło nagłówka karty */
            color: white; /* Biały tekst w nagłówku */
            text-align: center; /* Wyśrodkowanie tekstu w nagłówku */
            border-radius: 1rem 1rem 0 0; /* Zaokrąglone górne rogi nagłówka */
            padding: 1rem; /* Wewnętrzny odstęp w nagłówku */
        }
        .card-body {
            padding: 1.5rem; /* Wewnętrzny odstęp w ciele karty */
        }
        .list-group-item {
            border: none; /* Usunięcie obramowania elementów listy */
            padding: 1rem; /* Wewnętrzny odstęp elementów listy */
            text-align: center; /* Wyśrodkowanie tekstu w elementach listy */
        }
        .list-group-item:hover {
            background-color: #e9ecef; /* Jasnoszare tło elementu listy po najechaniu */
        }
        .list-group-item.active {
            background-color: #007bff; /* Niebieskie tło aktywnego elementu listy */
            color: white; /* Biały tekst aktywnego elementu listy */
        }
        .btn-primary {
            background-color: #007bff; /* Niebieskie tło przycisku */
            border-color: #007bff; /* Niebieski kolor obramowania przycisku */
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Ciemniejszy niebieski kolor tła przycisku po najechaniu */
            border-color: #0056b3; /* Ciemniejszy niebieski kolor obramowania przycisku po najechaniu */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Panel Administratora</h2>
                <p>Witaj w panelu administratora. Zarządzaj sklepem i jego użytkownikami.</p>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="zarzadzaj_uzytkownikami.php" class="list-group-item list-group-item-action">
                        Zarządzaj Użytkownikami (Klienci)
                    </a>
                    <a href="zarzadzaj_pracownikami.php" class="list-group-item list-group-item-action">
                        Zarządzaj Pracownikami
                    </a>
                    <a href="zarzadzaj_produktami.php" class="list-group-item list-group-item-action">
                        Zarządzaj Produktami
                    </a>
                    <a href="zarzadzaj_zamowieniami.php" class="list-group-item list-group-item-action">
                        Zarządzaj Zamówieniami
                    </a>
                    <a href="moderuj_opinie.php" class="list-group-item list-group-item-action">
                        Moderuj Opinie
                    </a>
                    <a href="index.php" class="list-group-item list-group-item-action">
                        Wróć na stronę główną
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close(); // Zamknięcie połączenia z bazą danych.
?>