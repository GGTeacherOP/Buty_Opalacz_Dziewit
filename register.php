<?php
session_start(); // Uruchomienie sesji
$zalogowany = isset($_SESSION['username']); // Sprawdzenie, czy użytkownik jest zalogowany
?>
<?php
$host = 'localhost';
$uzytkownik = 'root';
$haslo_db = '';
$nazwa_bazy = 'buty';

$polaczenie = new mysqli($host, $uzytkownik, $haslo_db, $nazwa_bazy);

if ($polaczenie->connect_error) {
    die("Błąd połączenia: " . $polaczenie->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Zapisz hasło bez hashowania (TYLKO DO NAUKI)
    $sql = "INSERT INTO klienci (nazwa_uzytkownika, email, haslo) VALUES (?, ?, ?)";
    $stmt = $polaczenie->prepare($sql);

    if (!$stmt) {
        die("Błąd zapytania: " . $polaczenie->error);
    }

    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
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
        echo "Błąd podczas rejestracji: " . $stmt->error;
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
    <title>Rejestracja</title>
    <link rel="stylesheet" href="css/style.css" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   <style>
    .auth-container {
        max-width: 400px;
        margin: 4rem auto;
        background: #fff;
        padding: 2.5rem; /* Nieco większe wewnętrzne odstępy */
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.15); /* Bardziej wyrazisty cień */
    }

    .auth-container h2 {
        text-align: center;
        margin-bottom: 2rem; /* Większy odstęp od formularza */
        color: #333; /* Ciemniejszy kolor nagłówka */
    }

    .auth-container input {
        width: 100%;
        padding: 0.8rem; /* Nieco większe wypełnienie */
        margin-bottom: 1.2rem; /* Większy dolny margines */
        border: 1px solid #ddd; /* Jaśniejsza ramka */
        border-radius: 8px; /* Większe zaokrąglenie */
        font-size: 1rem;
        color: #333; /* Domyślny kolor tekstu */
    }

    .auth-container input::placeholder {
        color: #777; /* Delikatnie przyciemniony placeholder */
    }

    .auth-container select {
        width: 100%;
        padding: 0.8rem;
        margin-bottom: 1.2rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        appearance: none;
        background-color: #f8f9fa; /* Jeszcze jaśniejszy szary */
        background-image: url('data:image/svg+xml;utf8,<svg fill="currentColor" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 0.8rem center;
        background-size: 1.2em; /* Nieco mniejsza strzałka */
        color: #333;
    }

    .auth-container select option {
        padding: 0.6rem;
        font-size: 1rem;
        background-color: #fff;
        color: #333;
    }

    .auth-container button {
        width: 100%;
        padding: 1rem; /* Większe wypełnienie przycisku */
        background-color: #007bff;
        border: none;
        color: white;
        font-size: 1.1rem; /* Nieco większa czcionka przycisku */
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out; /* Płynne przejście hover */
    }

    .auth-container button:hover {
        background-color: #0056b3;
    }

    .auth-container input:focus,
    .auth-container select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        outline: none;
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