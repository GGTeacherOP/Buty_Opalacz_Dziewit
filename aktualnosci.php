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
        .news-section {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1rem;
      font-size: 25px;
    }

    .news-title {
      text-align: center;
      font-size: 2.2rem;
      margin-bottom: 2rem;
    }

    

    .news-card {
      background-color: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 0;
      overflow: hidden;
      transition: transform 0.3s;
      border-radius: 20px;
    }

    .news-card:hover {
      transform: translateY(-5px);
    }

    .news-card img {
      width: 200px;
      height: 250px;
      object-fit: cover;
      float: left;
    }

    .news-card-content {
      padding: 1rem;
    }

    .news-card-content h3 {
      margin-bottom: 0.5rem;
      color: #111;
    }

    .news-card-content p {
      font-size: 1rem;
      color: #444;
    }

    .news-card-content .date {
      display: block;
      margin-top: 1rem;
      font-size: 0.85rem;
      color: #999;
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
    <nav>
        <p>Sprawdź co nowego u nas!</p>
    </nav>
    <main class="news-section">
        <h1 class="news-title">📰 Najnowsze aktualności</h1>
        <div class="news-cards">
          <a href="aktualnosciCampusy.php" style="text-decoration: none; color: inherit; display: block;">
      <div class="news-card">
        <img src="img/aktualnosci/2.png" class="nowosc" alt="Przykładowe zdjęcie">
        <div class="news-card-content">
          <h3>👟 Nowa Kolekcja Campusów</h3>
          <p>    Przedstawiamy najnowszą kolekcję butów Adidas Campus – klasyka w nowoczesnym wydaniu! Idealne na co dzień, wykonane z wysokiej jakości materiałów, zapewniają maksymalny komfort i styl. Dostępne w wielu wariantach kolorystycznych.</p>
          <p class="date">         2 maja 2025</p>
        </div>
      </a>
        </div>
    
  <br>
    <a href="aktualnosciJordany.php" style="text-decoration: none; color: inherit; display: block;">
      <div class="news-card">
        <img src="img/aktualnosci/3.png" class="nowosc" alt="Buty Jordan">
        <div class="news-card-content">
          <h3>  🔥 Nowa kolekcja Jordan & Nike</h3>
          <p>   Do oferty trafiły limitowane modele Air Jordan 1, Air Jordan 1 High oraz nowości od Nike! Wyjątkowy design, precyzja wykonania i wygoda – to cechy, które wyróżniają tę kolekcję. Idealne dla fanów streetwearu i sneakerheadów.</p>
          <p class="date"> 28 kwietnia 2025</p>
        </div>
      </div>
    </a>
    
  <br>
  
  <a href="aktualnosciKlapki.php" style="text-decoration: none; color: inherit; display: block;">
    <div class="news-card">
        <img src="img/aktualnosci/5.png" alt="klapki">
        <div class="news-card-content">
          <h3>  👟 Nowa kolekcja klapkow</h3>
          <p>   Nowa kolekcja Klapkow Obczaj co nowego czekaja na ciebie Klapki z roznych marek NIKE ADIDASA I JORDANA bardzo Korzystnych cenach zobaczcie sami!!!</p>
          <span class="date"> 29 kwietnia 2025</span>
        </div>
      </div>
      </a>
      </main>
    </div>
    <footer>
        <p>&copy; 2025 Sklep z Butami | kontakt@buty.pl</p>
    </footer>
    </div>
</body>
</html>




    
  