<?php
// Rozpoczęcie sesji PHP, umożliwiającej dostęp do zmiennych sesyjnych.
session_start();
// Dołączenie pliku 'auth_utils.php', który prawdopodobnie zawiera funkcje autoryzacji, takie jak 'czy_ma_role'.
include 'auth_utils.php';

// Sprawdzenie, czy zalogowany użytkownik posiada jedną z wymaganych ról: 'kierownik', 'admin' lub 'szef'.
// Funkcja 'czy_ma_role' (z pliku 'auth_utils.php') zwraca true, jeśli użytkownik ma przypisaną jedną z tych ról.
if (!czy_ma_role(['kierownik', 'admin', 'szef'])) {
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

// Inicjalizacja zmiennej na komunikat o błędzie. Domyślnie jest pusta.
$error = "";
// Sprawdzenie, czy żądanie do tego skryptu zostało wysłane metodą POST (np. po kliknięciu przycisku "Dodaj").
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobranie danych z formularza przesłanego metodą POST i przypisanie ich do zmiennych.
    $nazwa_uzytkownika = $_POST['nazwa_uzytkownika'];
    $haslo = $_POST['haslo'];
    $email = $_POST['email'];
    $stanowisko = $_POST['stanowisko'];
    $pensja = $_POST['pensja'];
    $data_zatrudnienia = $_POST['data_zatrudnienia']; // Pobranie daty zatrudnienia z formularza.

    // Przygotowanie zapytania SQL do wstawienia nowego pracownika do tabeli 'pracownicy'.
    // Użycie prepared statements (parametryzowanych zapytań) dla bezpieczeństwa przed SQL Injection.
    $sql = "INSERT INTO pracownicy (nazwa_uzytkownika, haslo, email, stanowisko, pensja, data_zatrudnienia)
            VALUES (?, ?, ?, ?, ?, ?)";
    // Przygotowanie zapytania SQL na utworzonym połączeniu z bazą danych.
    $stmt = $conn->prepare($sql);
    // Powiązanie parametrów z zapytaniem SQL. "ssssds" określa typy danych kolejnych parametrów:
    // s - string (nazwa_uzytkownika, haslo, email, stanowisko, data_zatrudnienia)
    // d - double (pensja)
    $stmt->bind_param("ssssds", $nazwa_uzytkownika, $haslo, $email, $stanowisko, $pensja, $data_zatrudnienia);

    // Wykonanie przygotowanego zapytania SQL.
    if ($stmt->execute()) {
        // Jeśli zapytanie wykonało się pomyślnie, następuje przekierowanie na stronę zarządzania pracownikami.
        header("Location: zarzadzaj_pracownikami.php");
        // Zakończenie wykonywania bieżącego skryptu po przekierowaniu.
        exit;
    } else {
        // Jeśli wystąpił błąd podczas wykonywania zapytania, komunikat o błędzie zostaje zapisany w zmiennej $error.
        $error = "Błąd dodawania pracownika: " . $stmt->error;
    }

    // Zamknięcie przygotowanego zapytania, aby zwolnić zasoby.
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Kierownika - Dodaj Pracownika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Jasnoszare tło strony */
        }
        .container {
            margin-top: 50px; /* Górny margines kontenera, oddalający go od góry okna. */
        }
        .card {
            border-radius: 1rem; /* Zaokrąglone rogi kontenera z formularzem. */
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
        .form-label {
            font-weight: bold; /* Pogrubienie etykiet formularza. */
        }
        .btn-primary {
            background-color: #007bff; /* Niebieskie tło głównego przycisku. */
            border-color: #007bff; /* Niebieska ramka głównego przycisku. */
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Ciemniejszy niebieski kolor tła przy najechaniu kursorem. */
            border-color: #0056b3; /* Ciemniejsza niebieska ramka przy najechaniu kursorem. */
        }
        .alert-danger {
            margin-top: 1rem; /* Górny margines dla komunikatu o błędzie. */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Dodaj Pracownika</h2>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label for="nazwa_uzytkownika" class="form-label">Nazwa Użytkownika</label>
                        <input type="text" class="form-control" id="nazwa_uzytkownika" name="nazwa_uzytkownika" required>
                    </div>
                    <div class="mb-3">
                        <label for="haslo" class="form-label">Hasło</label>
                        <input type="password" class="form-control" id="haslo" name="haslo" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="stanowisko" class="form-label">Stanowisko</label>
                        <input type="text" class="form-control" id="stanowisko" name="stanowisko" required>
                    </div>
                    <div class="mb-3">
                        <label for="pensja" class="form-label">Pensja</label>
                        <input type="number" class="form-control" id="pensja" name="pensja" required>
                    </div>
                    <div class="mb-3">
                        <label for="data_zatrudnienia" class="form-label">Data Zatrudnienia</label>
                        <input type="date" class="form-control" id="data_zatrudnienia" name="data_zatrudnienia" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                    <a href="zarzadzaj_pracownikami.php" class="btn btn-secondary">Anuluj</a>
                </form>
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