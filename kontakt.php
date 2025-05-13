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
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="img/favi2.png" type="image/png">
    <style>
        .calosc{
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        #wstep{
            background-color: rgb(134, 134, 134);
            height: 60px;
            font-size: large;
            margin-bottom: 2rem;
            color:rgb(228, 228, 228);
            padding: 6px;
            text-align: center;
            border-radius: 22px;
        }
        .uwaga{
            background-color: rgb(247, 255, 173);
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
            width:50%;
            float: left;
            
        }
        .kontakt{
            float: right;
        }
        form {
  max-width: 600px;
  margin: 2rem auto;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  background-color: #fff;
  padding: 2rem;
  border-radius: 15px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
 
form input,
form textarea {
  padding: 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  background-color: #fafafa;
}
form button {
  padding: 1rem;
  border: none;
  background-color: #222;
  color: white;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  border-radius: 8px;
}
 
form button:hover {
  background-color: #000;
  font-size:1.3rem;
  padding:0.83rem;
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
            <section class="kontakt">
            <h1>Czy masz inne pytania? Napisz do nas</h1>
            <form action="" method="post">
            <input type="text" name="name" placeholder="Twoje imię" required>
            <input type="email" name="email" placeholder="Twój email" required>
            <textarea name="message" placeholder="Napisz tutaj swoje pytanie" required></textarea>
            <button type="submit" class="submit">Wyślij</button>
            </form>
            </section>
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