<?php
// Rozpocznij sesję, aby móc korzystać ze zmiennych sesyjnych, np. do przechowywania komunikatów.
session_start();
// Załącz plik 'auth_utils.php', który prawdopodobnie zawiera funkcje do autoryzacji użytkowników.
include 'auth_utils.php';

// Sprawdź, czy aktualny użytkownik ma rolę 'admin' lub 'szef'.
// Funkcja 'czy_ma_role' (z pliku 'auth_utils.php') zwraca true, jeśli użytkownik ma jedną z tych ról.
if (!czy_ma_role(['admin', 'szef'])) {
    // Jeśli użytkownik nie ma wymaganych ról, przekieruj go na stronę 'index.php'.
    header("Location: index.php");
    // Zakończ wykonywanie bieżącego skryptu po przekierowaniu.
    exit;
}

// Utwórz nowe połączenie z bazą danych MySQL.
// Parametry połączenia to: host ('localhost'), użytkownik ('root'), hasło (puste w tym przypadku), nazwa bazy danych ('buty').
$conn = new mysqli("localhost", "root", "", "buty");
// Sprawdź, czy wystąpił błąd podczas łączenia z bazą danych.
if ($conn->connect_error) {
    // Jeśli połączenie nie powiodło się, wyświetl komunikat o błędzie i zakończ skrypt.
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Zapytanie SQL do pobrania listy wszystkich produktów z tabeli 'produkty'.
// Wybierane są kolumny: 'id_produktu', 'nazwa', 'marka', 'kategoria', 'cena'.
$sql = "SELECT id_produktu, nazwa, marka, kategoria, cena FROM produkty";
// Wykonaj zapytanie SQL na połączeniu z bazą danych i zapisz wynik w zmiennej '$result'.
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora - Zarządzaj Produktami</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Jasnoszare tło strony */
        }
        .container {
            margin-top: 50px; /* Górny margines kontenera, oddalający go od góry okna. */
        }
        .card {
            border-radius: 1rem; /* Zaokrąglone rogi kontenera z produktami. */
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
            margin-top: 20px; /* Górny margines tabeli z produktami. */
        }
        .btn-sm {
            margin-bottom: 5px; /* Mały dolny margines dla przycisków akcji. */
        }
        .alert {
            margin-bottom: 20px; /* Dolny margines dla komunikatów (np. o sukcesie lub błędzie). */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Zarządzaj Produktami</h2>
                <p>Lista produktów w sklepie i opcje zarządzania.</p>
            </div>
            <div class="card-body">

                <?php if (isset($_SESSION['komunikat'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['komunikat'] ?></div>
                    <?php unset($_SESSION['komunikat']); // Usunięcie komunikatu z sesji, aby nie wyświetlał się ponownie przy kolejnym załadowaniu strony. ?>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

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
                        // Sprawdź, czy zapytanie do bazy danych zwróciło jakieś wiersze.
                        if ($result->num_rows > 0) {
                            // Jeśli są jakieś produkty, iteruj po każdym wierszu wyniku.
                            while ($row = $result->fetch_assoc()) {
                                // Dla każdego produktu utwórz wiersz tabeli (<tr>).
                                echo "<tr>";
                                // Wyświetl ID produktu (<td> - komórka tabeli).
                                echo "<td>" . $row["id_produktu"] . "</td>";
                                // Wyświetl nazwę produktu.
                                echo "<td>" . $row["nazwa"] . "</td>";
                                // Wyświetl markę produktu.
                                echo "<td>" . $row["marka"] . "</td>";
                                // Wyświetl kategorię produktu.
                                echo "<td>" . $row["kategoria"] . "</td>";
                                // Wyświetl cenę produktu.
                                echo "<td>" . $row["cena"] . "</td>";
                                // Kolumna z przyciskami akcji (Edytuj i Usuń).
                                echo "<td>
                                        <a href='edytuj_produkt.php?id=" . $row["id_produktu"] . "' class='btn btn-primary btn-sm'>Edytuj</a>
                                        <form method='post' action='usun_produkt.php' style='display:inline-block;'>
                                            <input type='hidden' name='id_produktu' value='" . $row["id_produktu"] . "'>
                                            <button type='submit' class='btn btn-danger btn-sm' onclick=\"return confirm('Czy na pewno chcesz usunąć ten produkt?')\">Usuń</button>
                                        </form>
                                    </td>";
                                // Koniec wiersza tabeli.
                                echo "</tr>";
                            }
                        } else {
                            // Jeśli nie ma żadnych produktów w bazie danych, wyświetl odpowiedni komunikat w jednej kolumnie rozciągniętej na całą szerokość tabeli.
                            echo "<tr><td colspan='6'>Brak produktów w bazie danych.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="dodaj_produkt.php" class="btn btn-success">Dodaj Produkt</a>
                <a href="panel_admina.php" class="btn btn-secondary">Wróć do Panelu Admina</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Zamknij połączenie z bazą danych, aby zwolnić zasoby.
$conn->close();
?>