<?php
// Rozpoczęcie sesji, umożliwiającej dostęp do zmiennych sesyjnych
session_start();
// Załączenie pliku z funkcjami uwierzytelniającymi
include 'auth_utils.php';

// Sprawdzenie, czy użytkownik ma rolę 'admin' lub 'szef'
if (!czy_ma_role(['admin', 'szef'])) {
    // Jeśli użytkownik nie ma wymaganej roli, zostaje przekierowany na stronę index.php
    header("Location: index.php");
    // Zakończenie wykonywania bieżącego skryptu
    exit;
}

// Utworzenie nowego połączenia z bazą danych MySQL
$conn = new mysqli("localhost", "root", "", "buty");
// Sprawdzenie, czy wystąpił błąd podczas połączenia z bazą danych
if ($conn->connect_error) {
    // Jeśli połączenie nie powiodło się, wyświetlenie komunikatu o błędzie i zakończenie skryptu
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Inicjalizacja zmiennej na komunikaty o błędach
$error = "";
// Sprawdzenie, czy żądanie zostało wysłane metodą POST (po kliknięciu "Zapisz")
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobranie danych z formularza i przypisanie ich do zmiennych
    $id_produktu = $_POST['id_produktu'];
    $nazwa = $_POST['nazwa'];
    $marka = $_POST['marka'];
    $kategoria = $_POST['kategoria'];
    $cena = $_POST['cena'];
    $opis = $_POST['opis'];
    $zdjecie = $_POST['url_zdjecia'];

    // Zapytanie SQL do zaktualizowania danych produktu w tabeli 'produkty'
    $sql = "UPDATE produkty SET
                nazwa='$nazwa',
                marka='$marka',
                kategoria='$kategoria',
                cena='$cena',
                opis='$opis',
                zdjecie='$zdjecie'
                WHERE id_produktu='$id_produktu'";

    // Wykonanie zapytania SQL
    if ($conn->query($sql) === TRUE) {
        // Jeśli aktualizacja przebiegła pomyślnie, ustawienie komunikatu w sesji
        $_SESSION['komunikat'] = "Produkt został zaktualizowany.";
        // Przekierowanie na stronę z listą produktów
        header("Location: produkty_szef.php");
        // Zakończenie wykonywania bieżącego skryptu
        exit;
    } else {
        // Jeśli wystąpił błąd podczas aktualizacji, zapisanie komunikatu o błędzie
        $error = "Błąd aktualizacji produktu: " . $conn->error;
    }
}

// Pobranie danych produktu do wypełnienia formularza edycji
if (isset($_GET['id'])) {
    // Pobranie ID produktu z parametru GET
    $id_produktu = $_GET['id'];
    // Zapytanie SQL do pobrania danych produktu o podanym ID
    $sql = "SELECT id_produktu, nazwa, marka, kategoria, cena, opis, url_zdjecia FROM produkty WHERE id_produktu='$id_produktu'";
    // Wykonanie zapytania SQL
    $result = $conn->query($sql);

    // Sprawdzenie, czy zapytanie zwróciło dokładnie jeden wiersz (oczekiwane dla unikalnego ID produktu)
    if ($result->num_rows == 1) {
        // Pobranie wiersza wynikowego jako asocjacyjnej tablicy
        $row = $result->fetch_assoc();
        // Przypisanie wartości z tablicy do odpowiednich zmiennych, które zostaną użyte w formularzu
        $nazwa = $row['nazwa'];
        $marka = $row['marka'];
        $kategoria = $row['kategoria'];
        $cena = $row['cena'];
        $opis = $row['opis'];
        $zdjecie = $row['url_zdjecia'];
    } else {
        // Jeśli nie znaleziono produktu o podanym ID, ustawienie komunikatu o błędzie w sesji
        $_SESSION['error'] = "Nie znaleziono produktu o podanym ID.";
        // Przekierowanie na stronę z listą produktów
        header("Location: produkty_szef.php");
        // Zakończenie wykonywania bieżącego skryptu
        exit;
    }
} else {
    // Jeśli nie przekazano ID produktu w parametrze GET, ustawienie komunikatu o błędzie w sesji
    $_SESSION['error'] = "Nieprawidłowe zapytanie.";
    // Przekierowanie na stronę z listą produktów
    header("Location: produkty_szef.php");
    // Zakończenie wykonywania bieżącego skryptu
    exit;
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora - Edytuj Produkt</title>
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Lekki cień pod kartą */
        }
        .card-header {
            background-color: #007bff; /* Niebieskie tło nagłówka */
            color: white; /* Biały tekst w nagłówku */
            text-align: center; /* Wyśrodkowanie tekstu w nagłówku */
            border-radius: 1rem 1rem 0 0; /* Zaokrąglenie tylko górnych rogów nagłówka */
            padding: 1rem; /* Wewnętrzny odstęp w nagłówku */
        }
        .card-body {
            padding: 1.5rem; /* Wewnętrzny odstęp w ciele karty */
        }
        .form-label {
            font-weight: bold; /* Pogrubiona etykieta formularza */
        }
        .btn-primary {
            background-color: #007bff; /* Niebieskie tło przycisku primary */
            border-color: #007bff; /* Niebieski kolor obramowania przycisku primary */
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Ciemniejszy niebieski kolor tła przycisku primary na hover */
            border-color: #0056b3; /* Ciemniejszy niebieski kolor obramowania przycisku primary na hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Edytuj Produkt</h2>
                <p>Zaktualizuj dane produktu.</p>
            </div>
            <div class="card-body">

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="post">
                    <input type="hidden" name="id_produktu" value="<?= $id_produktu ?>">
                    <div class="mb-3">
                        <label for="nazwa" class="form-label">Nazwa</label>
                        <input type="text" class="form-control" id="nazwa" name="nazwa" value="<?= $nazwa ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="marka" class="form-label">Marka</label>
                        <input type="text" class="form-control" id="marka" name="marka" value="<?= $marka ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategoria" class="form-label">Kategoria</label>
                        <input type="text" class="form-control" id="kategoria" name="kategoria" value="<?= $kategoria ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="cena" class="form-label">Cena</label>
                        <input type="number" step="0.01" class="form-control" id="cena" name="cena" value="<?= $cena ?>" required>
                    </div>
                     <div class="mb-3">
                        <label for="opis" class="form-label">Opis</label>
                        <textarea class="form-control" id="opis" name="opis" rows="3" required><?= $opis ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="zdjecie" class="form-label">Ścieżka do zdjęcia</label>
                        <input type="text" class="form-control" id="zdjecie" name="url_zdjecia" value="<?= $zdjecie ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                    <a href="produkty_szef.php" class="btn btn-secondary">Anuluj</a>
                </form>
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