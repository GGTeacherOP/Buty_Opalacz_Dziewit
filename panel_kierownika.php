<?php
session_start(); // Rozpoczęcie sesji PHP, umożliwiającej dostęp do zmiennych sesyjnych.
include 'auth_utils.php'; // Dołączenie pliku z funkcjami autoryzacji, w tym 'czy_ma_role()'.

// Sprawdzenie, czy zalogowany użytkownik posiada jedną z wymaganych ról: 'kierownik', 'admin' lub 'szef'.
if (!czy_ma_role(['kierownik', 'admin', 'szef'])) {
    header("Location: index.php"); // Jeśli użytkownik nie ma odpowiedniej roli, przekieruj go na stronę główną.
    exit; // Zakończ wykonywanie bieżącego skryptu po przekierowaniu.
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Kierownika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Jasnoszare tło strony */
        }
        .container {
            margin-top: 50px; /* Górny margines kontenera, odsunięcie od góry okna */
        }
        .card {
            border-radius: 1rem; /* Zaokrąglone rogi karty */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Delikatny cień karty, nadający efekt głębi */
        }
        .card-header {
            background-color: #007bff; /* Niebieskie tło nagłówka karty */
            color: white; /* Biały kolor tekstu w nagłówku */
            text-align: center; /* Wyśrodkowanie tekstu w nagłówku */
            border-radius: 1rem 1rem 0 0; /* Zaokrąglenie tylko górnych rogów nagłówka */
            padding: 1rem; /* Wewnętrzny odstęp w nagłówku */
        }
        .card-body {
            padding: 1.5rem; /* Wewnętrzny odstęp w ciele karty */
        }
        .list-group-item {
            border: none; /* Usunięcie domyślnego obramowania elementów listy */
            padding: 1rem; /* Wewnętrzny odstęp w elementach listy */
            text-align: center; /* Wyśrodkowanie tekstu w elementach listy */
        }
        .list-group-item:hover {
            background-color: #e9ecef; /* Jasnoszare tło elementu listy po najechaniu kursorem */
        }
        .list-group-item.active {
            background-color: #007bff; /* Niebieskie tło aktywnego elementu listy */
            color: white; /* Biały kolor tekstu aktywnego elementu listy */
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
                <h2>Panel Kierownika</h2>
                <p>Witaj w panelu zarządzania sklepu. Wybierz opcję z listy poniżej.</p>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="zamowienia_kierownika.php" class="list-group-item list-group-item-action">
                        Zarządzaj Zamówieniami
                    </a>
                    <a href="produkty_kierownika.php" class="list-group-item list-group-item-action">
                        Zarządzaj Produktami
                    </a>
                    <a href="pracownicy.php" class="list-group-item list-group-item-action">
                        Zarządzaj Pracownikami
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
