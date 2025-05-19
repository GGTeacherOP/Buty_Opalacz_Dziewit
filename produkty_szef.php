<?php
session_start(); // Rozpoczęcie sesji PHP.
include 'auth_utils.php'; // Dołączenie pliku z funkcjami autoryzacji.

// Sprawdzenie, czy zalogowany użytkownik ma rolę 'admin', 'szef' lub 'kierownik'.
if (!czy_ma_role(['admin', 'szef', 'kierownik'])) {
    header("Location: index.php"); // Jeśli nie ma odpowiedniej roli, przekieruj na stronę główną.
    exit; // Zakończ wykonywanie skryptu.
}

// Nawiązanie połączenia z bazą danych MySQL.
$conn = new mysqli("localhost", "root", "", "buty");
// Sprawdzenie, czy wystąpił błąd podczas łączenia z bazą danych.
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error); // Wyświetlenie błędu i zatrzymanie skryptu.
}

// Zapytanie SQL do pobrania listy wszystkich produktów z bazy danych, posortowanych alfabetycznie według nazwy.
$sql = "SELECT id_produktu, nazwa, marka, kategoria, cena FROM produkty ORDER BY nazwa ASC";
$result = $conn->query($sql); // Wykonanie zapytania SQL i zapisanie wyniku.

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Szefa - Produkty</title>
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Delikatny cień karty dla efektu głębi */
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
        .table {
            margin-top: 20px; /* Górny margines tabeli, oddzielenie od innych elementów */
        }
        .btn-sm {
            margin-right: 5px; /* Prawy margines dla małych przycisków, odstęp między nimi */
        }
        .btn-primary {
            background-color: #007bff; /* Niebieskie tło przycisku podstawowego */
            border-color: #007bff; /* Niebieski kolor obramowania przycisku podstawowego */
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Ciemniejszy niebieski kolor tła przycisku podstawowego po najechaniu */
            border-color: #0056b3; /* Ciemniejszy niebieski kolor obramowania przycisku podstawowego po najechaniu */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Zarządzaj Produktami</h2>
                <p>Przeglądaj i zarządzaj produktami w sklepie.</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nazwa</th>
                                <th>Marka</th>
                                <th>Kategoria</th>
                                <th>Cena</th>
                                <th>Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Sprawdzenie, czy zapytanie zwróciło jakieś wiersze (produkty).
                            if ($result->num_rows > 0) {
                                // Pętla przechodząca przez każdy wiersz (każdy produkt) zwrócony przez zapytanie.
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["id_produktu"] . "</td>"; // Wyświetlenie ID produktu.
                                    echo "<td>" . $row["nazwa"] . "</td>"; // Wyświetlenie nazwy produktu.
                                    echo "<td>" . $row["marka"] . "</td>"; // Wyświetlenie marki produktu.
                                    echo "<td>" . $row["kategoria"] . "</td>"; // Wyświetlenie kategorii produktu.
                                    echo "<td>" . $row["cena"] . "</td>"; // Wyświetlenie ceny produktu.
                                    echo "<td>
                                            <a href='edytuj_produkt_szef.php?id=" . $row["id_produktu"] . "' class='btn btn-primary btn-sm'>Edytuj</a>
                                            <a href='usun_produkt_szef.php?id=" . $row["id_produktu"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Czy na pewno chcesz usunąć ten produkt?\");'>Usuń</a>
                                        </td>"; // Wyświetlenie przycisków do edycji i usunięcia produktu.
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>Brak produktów w bazie danych.</td></tr>"; // Wyświetlenie komunikatu, jeśli nie ma żadnych produktów.
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <a href="dodaj_produkt_szef.php" class="btn btn-success">Dodaj Produkt</a>
                <a href="panel_szefa.php" class="btn btn-secondary">Wróć do Panelu Szefa</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close(); // Zamknięcie połączenia z bazą danych.
?>