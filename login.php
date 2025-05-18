<?php
session_start();

$host = 'localhost';
$uzytkownik = 'root';
$haslo_db = '';
$nazwa_bazy = 'buty';

$polaczenie = new mysqli($host, $uzytkownik, $haslo_db, $nazwa_bazy);
if ($polaczenie->connect_error) {
    die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error);
}

$wiadomosc_bledu = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Sprawdzenie klientów
    $sql_klienci = "SELECT id_klienta, nazwa_uzytkownika, haslo, rola FROM klienci WHERE nazwa_uzytkownika = ?"; // Dodano rola
    $stmt_klienci = $polaczenie->prepare($sql_klienci);

    if ($stmt_klienci) {
        $stmt_klienci->bind_param("s", $username);
        $stmt_klienci->execute();
        $wynik_klienci = $stmt_klienci->get_result();

        if ($wynik_klienci->num_rows === 1) {
            $uzytkownik_klienci = $wynik_klienci->fetch_assoc();
            if ($password == $uzytkownik_klienci['haslo']) {
                $_SESSION['username'] = htmlspecialchars($uzytkownik_klienci['nazwa_uzytkownika']);
                $_SESSION['rola'] = $uzytkownik_klienci['rola']; // Pobranie roli klienta
                $_SESSION['id_uzytkownika'] = $uzytkownik_klienci['id_klienta'];
                $_SESSION['zalogowano_pomyslnie'] = true;

                // Pobierz koszyk klienta
                if ($_SESSION['rola'] === 'klient') { // Sprawdzenie roli
                    $id_klienta = $_SESSION['id_uzytkownika'];
                    $sql_koszyk = "SELECT ez.*, p.cena, p.nazwa, p.url_zdjecia
                                        FROM elementy_zamowienia ez
                                        JOIN produkty p ON ez.id_produktu = p.id_produktu
                                        WHERE ez.id_klienta = ?";
                    $stmt_koszyk = $polaczenie->prepare($sql_koszyk);

                    if ($stmt_koszyk) {
                        $stmt_koszyk->bind_param("i", $id_klienta);
                        $stmt_koszyk->execute();
                        $wynik_koszyk = $stmt_koszyk->get_result();
                        $_SESSION['koszyk'] = $wynik_koszyk->fetch_all(MYSQLI_ASSOC);
                    }
                }
            } else {
                $wiadomosc_bledu = "Nieprawidłowe hasło.";
            }
        } else {
            // Sprawdzenie pracowników
            $sql_pracownicy = "SELECT id_pracownika, nazwa_uzytkownika, haslo, stanowisko FROM pracownicy WHERE nazwa_uzytkownika = ?";
            $stmt_pracownicy = $polaczenie->prepare($sql_pracownicy);

            if ($stmt_pracownicy) {
                $stmt_pracownicy->bind_param("s", $username);
                $stmt_pracownicy->execute();
                $wynik_pracownicy = $stmt_pracownicy->get_result();

                if ($wynik_pracownicy->num_rows === 1) {
                    $uzytkownik_pracownicy = $wynik_pracownicy->fetch_assoc();
                    if ($password == $uzytkownik_pracownicy['haslo']) {
                        $_SESSION['username'] = htmlspecialchars($uzytkownik_pracownicy['nazwa_uzytkownika']);
                        $_SESSION['rola'] = $uzytkownik_pracownicy['stanowisko']; // Pobranie stanowiska jako roli
                        $_SESSION['id_uzytkownika'] = $uzytkownik_pracownicy['id_pracownika'];
                        $_SESSION['zalogowano_pomyslnie'] = true;
                        // Nie pobieramy koszyka dla pracowników
                    } else {
                        $wiadomosc_bledu = "Nieprawidłowe hasło.";
                    }
                } else {
                    $wiadomosc_bledu = "Nieprawidłowy użytkownik.";
                }
                $stmt_pracownicy->close();
            } else {
                $wiadomosc_bledu = "Błąd w zapytaniu SQL (pracownicy).";
            }
        }
        $stmt_klienci->close();
    } else {
        $wiadomosc_bledu = "Błąd w zapytaniu SQL (klienci).";
    }
}

$polaczenie->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="icon" href="img/favi2.png" type="image/png" />
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

        .auth-container input, .auth-container button {
            width: 100%;
            padding: 0.7rem;
            margin-bottom: 1rem;
            border-radius: 6px;
            font-size: 1rem;
        }

        .auth-container input {
            border: 1px solid #ccc;
        }

        .auth-container button {
            background-color: #0c7a43;
            border: none;
            color: white;
            cursor: pointer;
        }

        .auth-container button:hover {
            background-color: #095c30;
        }

        .error-message {
            color: red;
            margin-bottom: 1rem;
        }

        .modal {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999;
        }

        .modal-content {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            color: green;
            font-weight: bold;
            text-align: center;
            font-size: 1.2rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .register-link {
    text-align: center;
    margin-top: 1rem;
    font-size: 0.95rem;
}

.register-link p {
    margin: 0;
    color: #333;
}

.register-link a {
    display: inline-block;
    margin-top: 0.3rem;
    color: #0c7a43;
    font-weight: bold;
    text-decoration: none;
    border: 1px solid #0c7a43;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    transition: 0.3s ease;
}

.register-link a:hover {
    background-color: #0c7a43;
    color: white;
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

        <?php if (isset($_SESSION['zalogowano_pomyslnie']) && $_SESSION['zalogowano_pomyslnie']): ?>
            <div id="successModal" class="modal">
                <div class="modal-content">
                    <p>Pomyślnie zalogowano!</p>
                </div>
            </div>
            <script>
                setTimeout(() => {
                    // Przekierowanie w zależności od roli
                    <?php if ($_SESSION['rola'] === 'klient'): ?>
                        window.location.href = 'index.php';
                    <?php elseif (isset($_SESSION['rola']) && (strtolower($_SESSION['rola']) === 'szef' || strtolower($_SESSION['rola']) === 'admin' || strtolower($_SESSION['rola']) === 'kierownik' || strtolower($_SESSION['rola']) === 'pracownik sklepu')): ?>
                        window.location.href = 'index.php'; // Zmień na odpowiedni panel admina
                    <?php else: ?>
                        window.location.href = 'index.php'; // Domyślne przekierowanie
                    <?php endif; ?>
                }, 2000);
            </script>
            <?php unset($_SESSION['zalogowano_pomyslnie']); ?>
        <?php endif; ?>

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
            <div class="register-link">
    <p>Nie masz konta?</p>
    <a href="register.php">Zarejestruj się</a>
</div>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <h3>Kontakt</h3>
                <p>Buty Opalacz Dziewit</p>
                <p>ul. Kwiatowa 30, Mielec</p>
                <p>Tel: <a href="tel:+48123456789">+48 123 456 789</a></p>
                <p>Email: <a href="mailto:kontakt@butyopalacz.pl">kontakt@butyopalacz.pl</a></p>
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
                    <a href="https://facebook.com/butyopalacz" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://instagram.com/butyopalacz" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://twitter.com/butyopalacz" target="_blank"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Buty Opalacz Dziewit. Wszelkie prawa zastrzeżone.</p>
        </div>
    </footer>
</body>
</html>