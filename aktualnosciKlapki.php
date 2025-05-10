<?php
session_start(); // Uruchomienie sesji
$zalogowany = isset($_SESSION['username']); // Sprawdzenie, czy użytkownik jest zalogowany
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nowa Kolekcja Jordan</title>
  <link rel="stylesheet" href="css/style.css" />
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

    <main class="jordany-section">
        <h1 class="jordany-title">🔥 Kolekcja Klapkow – Maj 2025</h1>
        <div class="product-grid">
      
          <div class="product-card">
            <a href="klapkiNikeB.html" style="text-decoration: none; color: inherit; display: block;">
            <img src="img/Nike/KlapkiBiale/1.avif" alt="Jordan czerwone">
            <h3>Klapki Nike Białe</h3>
            <p>Klapki Fajne!</p>
            <p class="price">199 zł</p>
          </div>
          
      
          <div class="product-card">
            <a href="klapkiNikeC.php" style="text-decoration: none; color: inherit; display: block;">
            <img src="img/Nike/KlpakiCzarne/1.avif" alt="Jordan czerwone">
            <h3>Klapki Nike Czarne</h3>
            <p>Klapki Fajne!</p>
            <p class="price">199 zł</p>
          </div>
         


            <div class="product-card">
            <a href="AdidasKlapkiB.php" style="text-decoration: none; color: inherit; display: block;">
            <img src="img/Adidas/KlapkiBiale/1.avif" alt="Jordan czerwone">
            <h3>Klapki Adidas Białe</h3>
            <p>Klapki Fajne!</p>
            <p class="price">179zł</p>
          </div>
          
      
          <div class="product-card">
            <a href="AdidasKlapkiC.html" style="text-decoration: none; color: inherit; display: block;">
            <img src="img/Adidas/KlapkiCzarne/1.avif" alt="Jordan czerwone">
            <h3>Klapki Adidas Czarne</h3>
            <p>Klapki Fajne!</p>
            <p class="price">179 zł</p>
          </div>



            <div class="product-card">
            <a href="JordanKlapkiB.html" style="text-decoration: none; color: inherit; display: block;">
            <img src="img/Jordan/KlapkiBiale/1.avif" alt="Jordan czerwone">
            <h3>Klapki Jordan Białe</h3>
            <p>Klapki Fajne!</p>
            <p class="price">250 zł</p>
          </div>
          
      
          <div class="product-card">
            <a href="JordanKlapkiC.html" style="text-decoration: none; color: inherit; display: block;">
            <img src="img/Jordan/KlapkiCzarne/1.avif" alt="Jordan czerwone">
            <h3>Klapki Jordan Czarne</h3>
            <p>Klapki Fajne!</p>
            <p class="price">250 zł</p>
          </div>
          

        
      
        </div>
      </main>

    <footer>
      <p>&copy; 2025 Sklep z Butami | kontakt@buty.pl</p>
    </footer>
  </div>
</body>
</html>
