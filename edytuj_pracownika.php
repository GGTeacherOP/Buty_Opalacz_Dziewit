<?php
// Rozpoczęcie sesji, umożliwiającej dostęp do zmiennych sesyjnych
session_start();
// Załączenie pliku z funkcjami uwierzytelniającymi
include 'auth_utils.php';

// Sprawdzenie, czy użytkownik ma rolę 'kierownik', 'admin' lub 'szef'
if (!czy_ma_role(['kierownik', 'admin', 'szef'])) {
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
    $id_pracownika = $_POST['id_pracownika'];
    $nazwa_uzytkownika = $_POST['nazwa_uzytkownika'];
    $email = $_POST['email'];
    $stanowisko = $_POST['stanowisko'];
    $pensja = $_POST['pensja'];

    // Zapytanie SQL do zaktualizowania danych pracownika w tabeli 'pracownicy'
    $sql = "UPDATE pracownicy SET
                nazwa_uzytkownika='$nazwa_uzytkownika',
                email='$email',
                stanowisko='$stanowisko',
                pensja='$pensja'
                WHERE id_pracownika='$id_pracownika'";

    // Wykonanie zapytania SQL
    if ($conn->query($sql) === TRUE) {
        // Jeśli aktualizacja przebiegła pomyślnie, przekierowanie na stronę z listą pracowników
        header("Location: pracownicy.php");
        // Zakończenie wykonywania bieżącego skryptu
        exit;
    } else {
        // Jeśli wystąpił błąd podczas aktualizacji, zapisanie komunikatu o błędzie
        $error = "Błąd edycji pracownika: " . $conn->error;
    }
}

// Pobranie danych pracownika do wypełnienia formularza edycji
if (isset($_GET['id'])) {
    // Pobranie ID pracownika z parametru GET
    $id_pracownika = $_GET['id'];
    // Zapytanie SQL do pobrania danych pracownika o podanym ID
    $sql = "SELECT id_pracownika, nazwa_uzytkownika, email, stanowisko, pensja FROM pracownicy WHERE id_pracownika='$id_pracownika'";
    // Wykonanie zapytania SQL
    $result = $conn->query($sql);
    // Sprawdzenie, czy zapytanie zwróciło dokładnie jeden wiersz (oczekiwane dla unikalnego ID pracownika)
    if ($result->num_rows == 1) {
        // Pobranie wiersza wynikowego jako asocjacyjnej tablicy
        $row = $result->fetch_assoc();
        // Przypisanie wartości z tablicy do odpowiednich zmiennych, które zostaną użyte w formularzu
        $nazwa_uzytkownika = $row['nazwa_uzytkownika'];
        $email = $row['email'];
        $stanowisko = $row['stanowisko'];
        $pensja = $row['pensja'];
    } else {
        // Jeśli nie znaleziono pracownika o podanym ID, przekierowanie na stronę z listą pracowników
        header("Location: pracownicy.php");
        // Zakończenie wykonywania bieżącego skryptu
        exit;
    }
} else {
    // Jeśli nie przekazano ID pracownika w parametrze GET, przekierowanie na stronę z listą pracowników
    header("Location: pracownicy.php");
    // Zakończenie wykonywania bieżącego skryptu
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Kierownika - Edytuj Pracownika</title>
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
        .alert-danger {
            margin-top: 1rem; /* Górny margines dla komunikatu o błędzie */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Edytuj Pracownika</h2>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form method="post">
                    <input type="hidden" name="id_pracownika" value="<?= $id_pracownika ?>">
                    <div class="mb-3">
                        <label for="nazwa_uzytkownika" class="form-label">Nazwa Użytkownika</label>
                        <input type="text" class="form-control" id="nazwa_uzytkownika" name="nazwa_uzytkownika" value="<?= $nazwa_uzytkownika ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="stanowisko" class="form-label">Stanowisko</label>
                        <input type="text" class="form-control" id="stanowisko" name="stanowisko" value="<?= $stanowisko ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="pensja" class="form-label">Pensja</label>
                        <input type="number" class="form-control" id="pensja" name="pensja" value="<?= $pensja ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                    <a href="pracownicy.php" class="btn btn-secondary">Anuluj</a>
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