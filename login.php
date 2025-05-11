
<?php
session_start(); // Rozpoczęcie sesji
$zalogowany = isset($_SESSION['username']); // Sprawdzenie, czy użytkownik jest zalogowany

$host = 'localhost'; // Adres hosta bazy danych
$uzytkownik = 'root'; // Nazwa użytkownika bazy danych
$haslo_db = ''; // Hasło do bazy danych
$nazwa_bazy = 'buty'; // Nazwa bazy danych

$polaczenie = new mysqli($host, $uzytkownik, $haslo_db, $nazwa_bazy); // Utworzenie połączenia z bazą danych
if ($polaczenie->connect_error) { // Sprawdzenie, czy wystąpił błąd połączenia
    die("Błąd połączenia: " . $polaczenie->connect_error); // Wyświetlenie komunikatu o błędzie i zakończenie skryptu
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Sprawdzenie, czy metoda żądania to POST
    $username = $_POST["username"]; // Pobranie nazwy użytkownika z danych POST
    $password = $_POST["password"]; // Pobranie hasła z danych POST

    $sql = "SELECT * FROM uzytkownicy WHERE nazwa_uzytkownika = ? AND haslo = ?"; // Zapytanie SQL do pobrania użytkownika po nazwie i haśle
    $stmt = $polaczenie->prepare($sql); // Przygotowanie zapytania SQL

    if (!$stmt) { // Sprawdzenie, czy przygotowanie zapytania się powiodło
        die("Błąd zapytania: " . $polaczenie->error); // Wyświetlenie komunikatu o błędzie i zakończenie skryptu
    }

    $stmt->bind_param("ss", $username, $password); // Powiązanie parametrów z zapytaniem SQL
    $stmt->execute(); // Wykonanie zapytania SQL
    $wynik = $stmt->get_result(); // Pobranie wyniku zapytania

    if ($wynik->num_rows === 1) { // Sprawdzenie, czy znaleziono dokładnie jednego użytkownika
        $uzytkownik = $wynik->fetch_assoc(); // Pobranie danych użytkownika jako asocjacyjna tablica
        $_SESSION['username'] = $uzytkownik['nazwa_uzytkownika']; // Zapisanie nazwy użytkownika w sesji
        $_SESSION['rola'] = $uzytkownik['rola']; // Zapisanie roli użytkownika w sesji

        // Ładne powitanie + przekierowanie
        echo "<!DOCTYPE html>
        <html lang='pl'>
        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='refresh' content='2;url=index.php'>
            <title>Logowanie</title>
            <style>
                body {
                    font-family: Arial;
                    background-color: #f8f9fa;
                    text-align: center;
                    padding-top: 50px;
                }
                .box {
                    background: white;
                    padding: 30px;
                    margin: auto;
                    width: 300px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
            </style>
        </head>
        <body>
        <div class='box'>
            <h2>Zalogowano jako <span style='color:#007bff;'>".htmlspecialchars($uzytkownik['nazwa_uzytkownika'])."</span></h2>
            <p>Za chwilę zostaniesz przekierowany na stronę główną...</p>
        </div>
        </body>
        </html>"; // Wyświetlenie komunikatu powitalnego i przekierowanie na stronę główną
    } else {
        echo "Nieprawidłowa nazwa użytkownika lub hasło."; // Wyświetlenie komunikatu o nieprawidłowych danych logowania
    }

    $stmt->close(); // Zamknięcie przygotowanego zapytania
}

$polaczenie->close(); // Zamknięcie połączenia z bazą danych
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Zaloguj się</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        .auth-container {
            max-width: 400px;
            margin: 4rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .auth-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .auth-container input {
            width: 100%;
            padding: 0.7rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }

        .auth-container button {
            width: 100%;
            padding: 0.8rem;
            background-color: #0c7a43;
            border: none;
            color: white;
            font-size: 1rem;
            border-radius: 6px;
            cursor: pointer;
        }

        .auth-container button:hover {
            background-color: #095c30;
        }

        .error-message {
            color: red;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <header>
        <a href="index.php">Strona Główna</a>
        <a href="sklep.php">Sklep</a>
        <a href="koszyk.php">Koszyk</a>
        <a href="kontakt.php">Kontakt</a>
        <a href="opinie.php">Opinie</a>
        <a href="aktualnosci.php">Aktualności</a>
        <?php if ($zalogowany): ?>
            <!-- Powitanie zalogowanego użytkownika -->
            <span style="float:right; margin-left: 10px; color:#007bff; font-weight: bold;">
                Witaj, <?= htmlspecialchars($_SESSION['username']) ?>!
            </span>
            <!-- Przycisk wylogowania -->
            <a href="logout.php" style="float:right;" class="zg">Wyloguj</a>
        <?php else: ?>
            <!-- Linki logowania i rejestracji -->
            <a href="login.php" class="zg">Zaloguj</a>
            <a href="register.php" class="zg">Zarejestruj</a>  
        <?php endif; ?>
    </header>

    <div class="auth-container">
        <h2>Zaloguj się</h2>
        <?php if (!empty($wiadomosc_bledu)): ?>
            <p class="error-message"><?= $wiadomosc_bledu ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Nazwa użytkownika" required />
            <input type="password" name="password" placeholder="Hasło" required />
            <button type="submit">Zaloguj</button>
        </form>
    </div>
        </div>
    <footer class="footer">
        <p>&copy; 2025 Sklep z Butami | kontakt@buty.pl</p>
    </footer>
</div>
</body>
</html>