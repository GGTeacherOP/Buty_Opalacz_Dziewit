<?php
session_start(); // Uruchomienie sesji
include 'auth_utils.php';
$zalogowany = isset($_SESSION['username']);
$rola = $_SESSION['rola'] ?? 'gość';  // Domyślnie 'gość' dla niezalogowanych
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nowa Kolekcja Jordan</title>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="icon" href="img/favi2.png" type="image/png" />
  <style>
    .jordany-section {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1rem;
      font-family: sans-serif;
    }

    .jordany-title {
      font-size: 2.2rem;
      text-align: center;
      margin-bottom: 2rem;
    }

    .product-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      justify-content: center;
    }

    .product-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      width: 280px;
      overflow: hidden;
      transition: transform 0.3s;
      text-align: center;
    }

    .product-card:hover {
      transform: scale(1.03);
    }

    .product-card img {
      width: 100%;
      height: 280px;
      object-fit: cover;
    }

    .product-card h3 {
      margin: 1rem 0 0.5rem;
      font-size: 1.2rem;
    }

    .product-card p {
      color: black;
      font-size: 1rem;
      padding: 0 1rem 1rem;
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
            <a href="aktualnosci.php" class="active">Aktualności</a>
                   <?php if ($zalogowany): ?>
                <span style="float:right; margin-left: 10px; color:#007bff; font-weight: bold;">
                    Witaj, <?= htmlspecialchars($_SESSION['username']) ?>! (<?= $rola ?>)
                </span>
                <a href="logout.php" style="float:right;" class="zg">Wyloguj</a>
            <?php else: ?>
                <a href="login.php" class="zg">Zaloguj</a>
                <a href="register.php" class="zg">Zarejestruj</a>
            <?php endif; ?>

           <?php if (czy_ma_role(['szef'])): ?>
        <a href="panel_szefa.php">Panel Szefa</a>
    <?php endif; ?>

    <?php if (czy_ma_role(['admin', 'szef'])): ?>
        <a href="panel_admina.php">Panel Admina</a> 
    <?php endif; ?>

    <?php if (czy_ma_role(['kierownik', 'admin', 'szef'])): ?>
        <a href="panel_kierownika.php">Panel Kierownika</a>
    <?php endif; ?>

    <?php if (czy_ma_role(['Pracownik sklepu', 'kierownik', 'admin', 'szef'])): ?>
        <a href="panel_pracownikow.php">Panel Pracownika</a>
    <?php endif; ?>

    </header>

    <main class="jordany-section">
        <h1 class="jordany-title">🔥 Kolekcja Campusow – Maj 2025</h1>
        <div class="product-grid">
      <a href="Campusy.php" style="text-decoration: none; color: inherit; display: block;">
          <div class="product-card">
            <img src="img/Adidas/Campus/1.avif" alt="Campusy Beżowe">
            <h3>Adidas Campus 00s Beżowe</h3>
            <p>Limitowana edycja z nowej kolekcji Bezowe idealne na lato</p>
            <p class="price">529 zł</p>
          </div>
      
          <a href="Campusy2.php" style="text-decoration: none; color: inherit; display: block;">
          <div class="product-card">
            <img src="img/Adidas/Campus/campus1.jpg" alt="Campusy Czarne">
            <h3>Adidas Campus 00s Czarne</h3>
            <p>Limitowana edycja z nowej kolekcji Czarne idealne na lato</p>
            <p class="price">529 zł</p>
          </div>
          </a>
      
          
      
        </div>
      </main>

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
