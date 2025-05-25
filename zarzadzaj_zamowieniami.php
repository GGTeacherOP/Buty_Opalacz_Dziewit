<?php
session_start(); // Rozpocznij lub wznów sesję, aby móc korzystać ze zmiennych sesyjnych.
include 'auth_utils.php'; // Dołącz plik z funkcjami pomocniczymi do autentykacji.

// Sprawdź, czy użytkownik ma rolę 'admin' lub 'szef'.
// Funkcja 'czy_ma_role' (z pliku auth_utils.php) powinna zwracać true, jeśli użytkownik ma jedną z tych ról.
if (!czy_ma_role(['admin', 'szef'])) {
    // Jeśli użytkownik nie ma wymaganych ról, przekieruj go na stronę główną (index.php).
    header("Location: index.php");
    exit; // Zakończ wykonywanie skryptu po przekierowaniu.
}

// Nawiąż połączenie z bazą danych MySQL.
$conn = new mysqli("localhost", "root", "", "buty");
// Sprawdź, czy wystąpił błąd podczas połączenia z bazą danych.
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error); // Wyświetl komunikat o błędzie i zakończ skrypt.
}

// Przygotuj zapytanie SQL, aby pobrać listę zamówień z widoku 'widok_zamowienia_z_klientami'.
// Widok ten prawdopodobnie łączy informacje o zamówieniach z informacjami o klientach.
// Wyniki są sortowane malejąco według daty zamówienia ('data_zamowienia DESC'), co oznacza, że najnowsze zamówienia będą na górze.
$sql = "SELECT * FROM widok_zamowienia_z_klientami ORDER BY data_zamowienia DESC";
// Wykonaj zapytanie SQL na bazie danych i zapisz wynik w zmiennej '$result'.
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora - Zarządzaj Zamówieniami</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; // Jasnoszare tło strony.
        }
        .container {
            margin-top: 50px; // Dodaj margines od góry, aby odsunąć kontener od krawędzi okna.
        }
        .card {
            border-radius: 1rem; // Zaokrąglone rogi kart.
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); // Delikatny cień pod kartą dla efektu głębi.
        }
        .card-header {
            background-color: #007bff; // Niebieskie tło nagłówka karty (kolor primary Bootstrap).
            color: white; // Biały tekst w nagłówku.
            text-align: center; // Wyśrodkuj tekst w nagłówku.
            border-radius: 1rem 1rem 0 0; // Zaokrąglone górne rogi nagłówka, dolne proste.
            padding: 1rem; // Wewnętrzny odstęp w nagłówku.
        }
        .card-body {
            padding: 1.5rem; // Większy wewnętrzny odstęp w ciele karty.
        }
        .table {
            margin-top: 20px; // Dodaj margines nad tabelą.
        }
        .btn-sm {
            margin-bottom: 5px; // Dodaj mały margines pod małymi przyciskami.
        }
        .alert {
            margin-bottom: 20px; // Dodaj margines pod komunikatami alert.
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Zarządzaj Zamówieniami</h2>
                <p>Lista zamówień klientów.</p>
            </div>
            <div class="card-body">

                <?php if (isset($_SESSION['komunikat'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['komunikat'] ?></div>
                    <?php unset($_SESSION['komunikat']); // Usuń komunikat z sesji po jego wyświetleniu. ?>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Zamówienia</th>
                            <th>Data Zamówienia</th>
                            <th>Kwota Całkowita</th>
                            <th>Klient</th>
                            <th>Email Klienta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sprawdź, czy zapytanie zwróciło jakieś wiersze.
                        if ($result->num_rows > 0) {
                            // Jeśli tak, iteruj po każdym wierszu wyniku.
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id_zamowienia"] . "</td>"; // Wyświetl ID zamówienia.
                                echo "<td>" . $row["data_zamowienia"] . "</td>"; // Wyświetl datę zamówienia.
                                echo "<td>" . $row["kwota_calkowita"] . "</td>"; // Wyświetl kwotę całkowitą zamówienia.
                                echo "<td>" . $row["klient"] . "</td>"; // Wyświetl nazwę klienta (prawdopodobnie z widoku).
                                echo "<td>" . $row["email"] . "</td>"; // Wyświetl email klienta (prawdopodobnie z widoku).
                                echo "</tr>";
                            }
                        } else {
                            // Jeśli nie ma żadnych zamówień w bazie danych, wyświetl odpowiedni komunikat.
                            echo "<tr><td colspan='5'>Brak zamówień w bazie danych.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
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