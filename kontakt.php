<?php
session_start(); // Uruchomienie sesji
$zalogowany = isset($_SESSION['username']); // Sprawdzenie, czy użytkownik jest zalogowany
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <title>Sklep z Butami – Strona główna</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="icon" href="img/favi2.png" type="image/png">
    <style>
        .calosc{
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        #wstep{
           background-color: #191970;
            height: 60px;
            font-size: large;
            margin-bottom: 2rem;
            color:rgb(228, 228, 228);
            padding: 6px;
            text-align: center;
            border-radius: 22px;
        }
        .uwaga{
            background-color: lightblue;
            font-size: 20px;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;

        }
        .uwaga:hover{
            transform: translateY(-5px);
        }
        .uwaga h2{
            color: rgb(109, 36, 36);
        }
        .info{
            font-size: 20px;
        }
    </style>
</head>
<body>
  <div class="wrapper">
    <header>
        <a href="index.php">Strona Główna</a>
            <a href="sklep.php">Sklep</a>
            <a href="koszyk.php">Koszyk</a>
            <a href="kontakt.html" class="active">Kontakt</a>
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
    <main>
        <div class="calosc">
            <div id="wstep">
                <h2>Cześć! Jak możemy pomóc?</h2>
            </div>
            <div class="uwaga">
                <h2>Uwaga!</h2>
                <p>W związku z dużą liczbą zgłoszeń, czas oczekiwania na rozmowę z konsultantem na infolinii znacznie się wydłużył.
                Jeśli nie możesz czekać, skontaktuj się z nami pod adresem <b>info@buty.pl</b><br><br>
                Prosimy o wskazanie numeru zamówienia oraz tematu sprawy w tytule wiadomości - pomoże nam to szybciej i skuteczniej zająć się Twoim zgłoszeniem.<br><br>
                Dziękujemy za zrozumienie i prosimy o cierpliwość.</p>
            </div><br><br><br>
            <div class="info">
                <h2>Informacje kontaktowe</h2>
                <p>Buty.pl - Mielec<br>
                +48 609 350 471<br>
                info@buty.pl</p>
            </div>
        </div>
      </main>
    </div>
    <footer>
        <p>&copy; 2025 Sklep z Butami | kontakt@buty.pl</p>
    </footer>
</body>
</html>