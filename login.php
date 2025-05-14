<?php
session_start();

$host = 'localhost';
$uzytkownik = 'root';
$haslo_db = '';
$nazwa_bazy = 'buty';

$polaczenie = new mysqli($host, $uzytkownik, $haslo_db, $nazwa_bazy);
if ($polaczenie->connect_error) {
    die("Błąd połączenia: " . $polaczenie->connect_error);
}

$wiadomosc_bledu = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Sprawdzenie w tabeli 'klienci'
    $sql_klienci = "SELECT id_klienta, nazwa_uzytkownika, haslo FROM klienci WHERE nazwa_uzytkownika = ?";
    $stmt_klienci = $polaczenie->prepare($sql_klienci);

    if ($stmt_klienci) {
        $stmt_klienci->bind_param("s", $username);
        $stmt_klienci->execute();
        $wynik_klienci = $stmt_klienci->get_result();

        if ($wynik_klienci->num_rows === 1) {
            $uzytkownik = $wynik_klienci->fetch_assoc();
            // Zmieniono na zwykłe porównanie haseł (NIEBEZPIECZNE!)
            if ($password == $uzytkownik['haslo']) {
                $_SESSION['username'] = htmlspecialchars($uzytkownik['nazwa_uzytkownika']);
                $_SESSION['rola'] = 'klient';
                $_SESSION['id_uzytkownika'] = $uzytkownik['id_klienta'];
                header("Location: index.php");
                exit;
            } else {
                $wiadomosc_bledu = "Nieprawidłowe hasło.";
            }
        }
        $stmt_klienci->close();
    }

    // Jeśli nie znaleziono w 'klienci', to sprawdzamy w 'pracownicy'
    if (empty($_SESSION['username'])) {
        $sql_pracownicy = "SELECT id_pracownika, nazwa_uzytkownika, haslo, stanowisko FROM pracownicy WHERE nazwa_uzytkownika = ?";
        $stmt_pracownicy = $polaczenie->prepare($sql_pracownicy);

        if ($stmt_pracownicy) {
            $stmt_pracownicy->bind_param("s", $username);
            $stmt_pracownicy->execute();
            $wynik_pracownicy = $stmt_pracownicy->get_result();

            if ($wynik_pracownicy->num_rows === 1) {
                $uzytkownik = $wynik_pracownicy->fetch_assoc();
                // Zmieniono na zwykłe porównanie haseł (NIEBEZPIECZNE!)
                if ($password == $uzytkownik['haslo']) {
                    $_SESSION['username'] = htmlspecialchars($uzytkownik['nazwa_uzytkownika']);
                    $_SESSION['rola'] = $uzytkownik['stanowisko'];
                    $_SESSION['id_uzytkownika'] = $uzytkownik['id_pracownika'];
                    header("Location: index.php");
                    exit;
                } else {
                    $wiadomosc_bledu = "Nieprawidłowe hasło.";
                }
            } else {
                $wiadomosc_bledu = "Nieprawidłowa nazwa użytkownika.";
            }
            $stmt_pracownicy->close();
        }
    }
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
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        <?php if (isset($_SESSION['username'])): ?>
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
      <div class="footer-container">
        <div class="footer-column">
          <h3>Kontakt</h3>
          <p>Buty Opalacz Dziewit</p>
          <p>ul. Kwiatowa 30, Mielec</p>
          <p>Tel: <a href="tel:+48123456789\">+48 123 456 789</a></p>
          <p>Email: <a href="mailto:kontakt@butyopalacz.pl\">kontakt@butyopalacz.pl</a></p>
        </div>
        <div class="footer-column">
          <h3>Godziny otwarcia</h3>
          <p>Poniedziałek – Piątek: 9:00 – 18:00</p>
          <p>Sobota: 10:00 – 14:00</p>
          <p>Niedziela: nieczynne</p>
        </div>
        <div class="footer-column">
          <h3>Śledź nas</h3>
          <div class="social-icons">
            <a href="https://facebook.com/butyopalacz" target="_blank" aria-label="Facebook">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://instagram.com/butyopalacz" target="_blank" aria-label="Instagram">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="https://twitter.com/butyopalacz" target="_blank" aria-label="Twitter">
              <i class="fab fa-twitter"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 Buty Opalacz Dziewit. Wszelkie prawa zastrzeżone.</p>
      </div>
    </footer>
</body>
</html>