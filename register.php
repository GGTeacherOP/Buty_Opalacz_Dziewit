<?php
session_start(); // Uruchomienie mechanizmu sesji, który umożliwia przechowywanie danych o użytkowniku między żądaniami.
$zalogowany = isset($_SESSION['username']); // Sprawdzenie, czy w sesji istnieje zmienna 'username'. Jeśli tak, użytkownik jest zalogowany.
?>
<?php
$host = 'localhost'; // Adres serwera bazy danych.
$uzytkownik = 'root'; // Nazwa użytkownika bazy danych.
$haslo_db = ''; // Hasło do bazy danych (w tym przypadku puste).
$nazwa_bazy = 'buty'; // Nazwa bazy danych, z którą chcemy się połączyć.

// Utworzenie nowego obiektu mysqli do połączenia z bazą danych.
$polaczenie = new mysqli($host, $uzytkownik, $haslo_db, $nazwa_bazy);

// Sprawdzenie, czy wystąpił błąd podczas łączenia z bazą danych.
if ($polaczenie->connect_error) {
    die("Błąd połączenia: " . $polaczenie->connect_error); // Wyświetlenie komunikatu o błędzie i zatrzymanie wykonywania skryptu.
}

// Sprawdzenie, czy metoda żądania HTTP to POST (czyli czy formularz został wysłany).
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"]; // Pobranie wartości pola 'username' z formularza.
    $email = $_POST["email"]; // Pobranie wartości pola 'email' z formularza.
    $password = $_POST["password"]; // Pobranie wartości pola 'password' z formularza.

    // Zapytanie SQL do wstawienia nowego klienta do tabeli 'klienci'.
    // Użyto prepared statements, aby zapobiec atakom SQL injection.
    $sql = "INSERT INTO klienci (nazwa_uzytkownika, email, haslo) VALUES (?, ?, ?)";
    $stmt = $polaczenie->prepare($sql); // Przygotowanie zapytania SQL do wykonania.

    // Sprawdzenie, czy przygotowanie zapytania się powiodło.
    if (!$stmt) {
        die("Błąd zapytania: " . $polaczenie->error); // Wyświetlenie błędu, jeśli przygotowanie się nie powiodło.
    }

    // Powiązanie parametrów z przygotowanym zapytaniem. "sss" oznacza, że wszystkie trzy parametry są typu string.
    $stmt->bind_param("sss", $username, $email, $password);

    // Wykonanie przygotowanego zapytania SQL.
    if ($stmt->execute()) {
        // Jeśli rejestracja przebiegła pomyślnie, wyświetl komunikat sukcesu ze stylami CSS wewnątrz znacznika <style>.
        echo '
        <style>
            .success-alert {
                max-width: 500px;
                margin: 50px auto;
                padding: 20px 25px;
                background-color: #e6ffed;
                border: 1px solid #b7eb8f;
                border-radius: 8px;
                color: #2f855a;
                font-family: Arial, sans-serif;
                font-size: 16px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                position: relative;
            }
            .success-alert::before {
                content: "✔";
                font-size: 20px;
                position: absolute;
                left: 15px;
                top: 18px;
            }
            .success-alert .message {
                padding-left: 30px;
            }
        </style>
        <div class="success-alert">
            <div class="message">
                Użytkownik zarejestrowany pomyślnie! Możesz się teraz <a href="login.php">zalogować</a>.
            </div>
        </div>';
    } else {
        // Jeśli wystąpił błąd podczas rejestracji, wyświetl komunikat o błędzie.
        echo "Błąd podczas rejestracji: " . $stmt->error;
    }

    // Zamknięcie przygotowanego zapytania.
    $stmt->close();
}

// Zamknięcie połączenia z bazą danych.
$polaczenie->close();
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Rejestracja</title>
    <link rel="stylesheet" href="css/style.css" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     <style>
     /* Style CSS dla formularza rejestracji */
     .auth-container {
         max-width: 400px; /* Maksymalna szerokość kontenera formularza */
         margin: 4rem auto; /* Wyśrodkowanie kontenera na stronie z górnym marginesem */
         background: #fff; /* Białe tło kontenera */
         padding: 2.5rem; /* Wewnętrzne odstępy w kontenerze */
         border-radius: 12px; /* Zaokrąglone rogi kontenera */
         box-shadow: 0 6px 15px rgba(0,0,0,0.15); /* Cień kontenera */
     }

     .auth-container h2 {
         text-align: center; /* Wyśrodkowanie nagłówka h2 */
         margin-bottom: 2rem; /* Dolny margines nagłówka h2 */
         color: #333; /* Ciemnoszary kolor nagłówka */
     }

     .auth-container input {
         width: 100%; /* Szerokość pól input równa szerokości kontenera */
         padding: 0.8rem; /* Wewnętrzne odstępy w polach input */
         margin-bottom: 1.2rem; /* Dolny margines pól input */
         border: 1px solid #ddd; /* Szara ramka pól input */
         border-radius: 8px; /* Zaokrąglone rogi pól input */
         font-size: 1rem; /* Rozmiar czcionki w polach input */
         color: #333; /* Domyślny kolor tekstu w polach input */
     }

     .auth-container input::placeholder {
         color: #777; /* Szary kolor tekstu placeholder */
     }

     .auth-container select {
         width: 100%;
         padding: 0.8rem;
         margin-bottom: 1.2rem;
         border: 1px solid #ddd;
         border-radius: 8px;
         font-size: 1rem;
         appearance: none; /* Ukrycie domyślnej strzałki selecta */
         background-color: #f8f9fa; /* Jasnoszare tło selecta */
         background-image: url('data:image/svg+xml;utf8,<svg fill="currentColor" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>'); /* Dodanie niestandardowej strzałki */
         background-repeat: no-repeat;
         background-position: right 0.8rem center;
         background-size: 1.2em;
         color: #333;
     }

     .auth-container select option {
         padding: 0.6rem;
         font-size: 1rem;
         background-color: #fff;
         color: #333;
     }

     .auth-container button {
         width: 100%; /* Szerokość przycisku równa szerokości kontenera */
         padding: 1rem; /* Wewnętrzne odstępy w przycisku */
         background-color: #007bff; /* Niebieskie tło przycisku */
         border: none; /* Usunięcie obramowania przycisku */
         color: white; /* Biały kolor tekstu przycisku */
         font-size: 1.1rem; /* Większy rozmiar czcionki w przycisku */
         border-radius: 8px; /* Zaokrąglone rogi przycisku */
         cursor: pointer; /* Zmiana kursora na wskazujący */
         transition: background-color 0.2s ease-in-out; /* Płynne przejście koloru tła przy hover */
     }

     .auth-container button:hover {
         background-color: #0056b3; /* Ciemniejszy niebieski kolor tła przy hover */
     }

     .auth-container input:focus,
     .auth-container select:focus {
         border-color: #007bff; /* Niebieski kolor ramki przy focusie */
         box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); /* Drobny cień przy focusie */
         outline: none; /* Usunięcie domyślnego obrysu focusa */
     }
 </style>
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
        <h2>Załóż konto</h2>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Nazwa użytkownika" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Hasło" required>

            <button type="submit">Zarejestruj się</button>
        </form>
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
</div>
</body>
</html>