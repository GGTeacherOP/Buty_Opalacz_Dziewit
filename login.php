<?php
session_start(); // Rozpoczęcie sesji PHP, umożliwiającej przechowywanie danych użytkownika między żądaniami.

// Dane do połączenia z bazą danych.
$host = 'localhost';
$uzytkownik = 'root';
$haslo_db = '';
$nazwa_bazy = 'buty';

// Nawiązanie połączenia z bazą danych MySQL.
$polaczenie = new mysqli($host, $uzytkownik, $haslo_db, $nazwa_bazy);
// Sprawdzenie, czy wystąpił błąd podczas łączenia z bazą danych.
if ($polaczenie->connect_error) {
    die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error); // Wyświetlenie błędu i zatrzymanie skryptu.
}

$wiadomosc_bledu = ""; // Inicjalizacja zmiennej na komunikaty o błędach logowania.

// Sprawdzenie, czy formularz logowania został wysłany metodą POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"]; // Pobranie nazwy użytkownika z formularza.
    $password = $_POST["password"]; // Pobranie hasła z formularza.

    // Sprawdzenie danych logowania w tabeli klientów.
    $sql_klienci = "SELECT id_klienta, nazwa_uzytkownika, haslo, rola FROM klienci WHERE nazwa_uzytkownika = ?"; // Zapytanie SQL wybierające dane klienta po nazwie użytkownika. Dodano 'rola'.
    $stmt_klienci = $polaczenie->prepare($sql_klienci); // Przygotowanie zapytania SQL.

    if ($stmt_klienci) {
        $stmt_klienci->bind_param("s", $username); // Powiązanie nazwy użytkownika z parametrem zapytania.
        $stmt_klienci->execute(); // Wykonanie zapytania.
        $wynik_klienci = $stmt_klienci->get_result(); // Pobranie wyników zapytania.

        // Sprawdzenie, czy znaleziono dokładnie jednego klienta o podanej nazwie użytkownika.
        if ($wynik_klienci->num_rows === 1) {
            $uzytkownik_klienci = $wynik_klienci->fetch_assoc(); // Pobranie wiersza wyniku jako tablicy asocjacyjnej.
            // Sprawdzenie, czy wprowadzone hasło zgadza się z hasłem w bazie danych.
            if ($password == $uzytkownik_klienci['haslo']) {
                // Ustawienie zmiennych sesji po pomyślnym zalogowaniu klienta.
                $_SESSION['username'] = htmlspecialchars($uzytkownik_klienci['nazwa_uzytkownika']); // Bezpieczne zapisanie nazwy użytkownika.
                $_SESSION['rola'] = $uzytkownik_klienci['rola']; // Pobranie i zapisanie roli klienta w sesji.
                $_SESSION['id_uzytkownika'] = $uzytkownik_klienci['id_klienta']; // Zapisanie ID klienta w sesji.
                $_SESSION['zalogowano_pomyslnie'] = true; // Flaga informująca o pomyślnym zalogowaniu.

                // Pobierz koszyk klienta z bazy danych.
                if ($_SESSION['rola'] === 'klient') { // Sprawdzenie, czy zalogowana osoba to klient.
                    $id_klienta = $_SESSION['id_uzytkownika']; // Pobranie ID klienta z sesji.
                    $sql_koszyk = "SELECT ez.*, p.cena, p.nazwa, p.url_zdjecia
                                     FROM elementy_zamowienia ez
                                     JOIN produkty p ON ez.id_produktu = p.id_produktu
                                     WHERE ez.id_klienta = ?"; // Zapytanie SQL pobierające elementy koszyka klienta.
                    $stmt_koszyk = $polaczenie->prepare($sql_koszyk); // Przygotowanie zapytania.

                    if ($stmt_koszyk) {
                        $stmt_koszyk->bind_param("i", $id_klienta); // Powiązanie ID klienta z parametrem.
                        $stmt_koszyk->execute(); // Wykonanie zapytania.
                        $wynik_koszyk = $stmt_koszyk->get_result(); // Pobranie wyników.
                        $_SESSION['koszyk'] = $wynik_koszyk->fetch_all(MYSQLI_ASSOC); // Zapisanie koszyka klienta w sesji.
                    }
                }
            } else {
                $wiadomosc_bledu = "Nieprawidłowe hasło."; // Ustawienie komunikatu o błędnym haśle.
            }
        } else {
            // Jeśli nie znaleziono klienta, sprawdź w tabeli pracowników.
            $sql_pracownicy = "SELECT id_pracownika, nazwa_uzytkownika, haslo, stanowisko FROM pracownicy WHERE nazwa_uzytkownika = ?"; // Zapytanie SQL dla pracowników.
            $stmt_pracownicy = $polaczenie->prepare($sql_pracownicy); // Przygotowanie zapytania.

            if ($stmt_pracownicy) {
                $stmt_pracownicy->bind_param("s", $username); // Powiązanie nazwy użytkownika.
                $stmt_pracownicy->execute(); // Wykonanie zapytania.
                $wynik_pracownicy = $stmt_pracownicy->get_result(); // Pobranie wyników.

                // Sprawdzenie, czy znaleziono pracownika.
                if ($wynik_pracownicy->num_rows === 1) {
                    $uzytkownik_pracownicy = $wynik_pracownicy->fetch_assoc(); // Pobranie danych pracownika.
                    // Sprawdzenie hasła pracownika.
                    if ($password == $uzytkownik_pracownicy['haslo']) {
                        // Ustawienie zmiennych sesji dla zalogowanego pracownika.
                        $_SESSION['username'] = htmlspecialchars($uzytkownik_pracownicy['nazwa_uzytkownika']);
                        $_SESSION['rola'] = $uzytkownik_pracownicy['stanowisko']; // Ustawienie stanowiska jako roli.
                        $_SESSION['id_uzytkownika'] = $uzytkownik_pracownicy['id_pracownika'];
                        $_SESSION['zalogowano_pomyslnie'] = true;
                        // Nie pobieramy koszyka dla pracowników.
                    } else {
                        $wiadomosc_bledu = "Nieprawidłowe hasło."; // Błędne hasło pracownika.
                    }
                } else {
                    $wiadomosc_bledu = "Nieprawidłowy użytkownik."; // Nie znaleziono użytkownika ani w klientach, ani w pracownikach.
                }
                $stmt_pracownicy->close(); // Zamknięcie zapytania dla pracowników.
            } else {
                $wiadomosc_bledu = "Błąd w zapytaniu SQL (pracownicy)."; // Błąd przygotowania zapytania dla pracowników.
            }
        }
        $stmt_klienci->close(); // Zamknięcie zapytania dla klientów.
    } else {
        $wiadomosc_bledu = "Błąd w zapytaniu SQL (klienci)."; // Błąd przygotowania zapytania dla klientów.
    }
}

$polaczenie->close(); // Zamknięcie połączenia z bazą danych.
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
        /* Style CSS dla kontenera logowania */
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

        /* Styl dla modalnego okienka po pomyślnym zalogowaniu */
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
        /* Style dla linku do rejestracji */
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
                // Skrypt JavaScript do przekierowania po pomyślnym zalogowaniu
                setTimeout(() => {
                    // Przekierowanie w zależności od roli użytkownika
                    <?php if ($_SESSION['rola'] === 'klient'): ?>
                        window.location.href = 'index.php'; // Klient wraca na stronę główną
                    <?php elseif (isset($_SESSION['rola']) && (strtolower($_SESSION['rola']) === 'szef' || strtolower($_SESSION['rola']) === 'admin' || strtolower($_SESSION['rola']) === 'kierownik' || strtolower($_SESSION['rola']) === 'pracownik sklepu')): ?>
                        window.location.href = 'index.php'; // Pracownicy i administracja na stronę główną (można zmienić na panel)
                    <?php else: ?>
                        window.location.href = 'index.php'; // Domyślne przekierowanie na stronę główną
                    <?php endif; ?>
                }, 2000); // Przekierowanie po 2 sekundach
            </script>
            <?php unset($_SESSION['zalogowano_pomyslnie']); // Usunięcie flagi zalogowania z sesji ?>
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