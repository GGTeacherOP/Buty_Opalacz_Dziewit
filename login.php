<?php
session_start();
$zalogowany = isset($_SESSION['username']);

$host = 'localhost';
$uzytkownik = 'root';
$haslo_db = '';
$nazwa_bazy = 'buty';

$polaczenie = new mysqli($host, $uzytkownik, $haslo_db, $nazwa_bazy);
if ($polaczenie->connect_error) {
    die("Błąd połączenia: " . $polaczenie->connect_error);
}

$wiadomosc_bledu = ""; // Inicjalizacja zmiennej na komunikaty błędów

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM uzytkownicy WHERE nazwa_uzytkownika = ? AND haslo = ?";
    $stmt = $polaczenie->prepare($sql);

    if (!$stmt) {
        die("Błąd zapytania: " . $polaczenie->error);
    }

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $wynik = $stmt->get_result();

    if ($wynik->num_rows === 1) {
        $uzytkownik = $wynik->fetch_assoc();
        $_SESSION['username'] = $uzytkownik['nazwa_uzytkownika'];
        $_SESSION['rola'] = $uzytkownik['rola'];
        $_SESSION['id_uzytkownika'] = $uzytkownik['id_uzytkownika'];  // ***KLUCZOWA POPRAWKA***

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
                <h2>Zalogowano jako <span style='color:#007bff;'>".htmlspecialchars($uzytkownik['nazwa_uzytkownika'])."</span>!</h2>
                <p>Za chwilę nastąpi przekierowanie...</p>
            </div>
        </body>
        </html>";
        exit;

    } else {
        $wiadomosc_bledu = "Nieprawidłowa nazwa użytkownika lub hasło.";
    }

    $stmt->close();
}

$polaczenie->close();
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
            <span style="float:right; margin-left: 10px; color:#007bff; font-weight: bold;">
                Witaj, <?= htmlspecialchars($_SESSION['username']) ?>!
            </span>
            <a href="logout.php" style="float:right;" class="zg">Wyloguj</a>
        <?php else: ?>
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
        <p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
    </div>
        </div>
    <footer class="footer">
        <p>&copy; 2025 Sklep z Butami | kontakt@buty.pl</p>
    </footer>
</body>
</html>
