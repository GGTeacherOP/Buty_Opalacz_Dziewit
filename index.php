<?php
session_start(); // Uruchomienie sesji
$zalogowany = isset($_SESSION['username']); // Sprawdzenie, czy użytkownik jest zalogowany
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep z Butami – Strona główna</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="icon" href="img/favi2.png" type="image/png">
</head>
<body>
  <div class="wrapper">
    <header>
        <!-- Nawigacja główna -->
        <a href="index.php" class="active">Strona Główna</a>
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
    
    <!-- Nawigacja boczna z kategoriami -->
    <nav>
        <p class="prz">Buty</p>
        <div class="zbior">
          <a href="snekarsy.html">Sneakersy</a>
          <a href="trampki.html">Trampki</a>
          <a href="butdb.html">Buty do biegania</a>
          <a href="butdtr.html">Buty treningowe</a>
          <a href="klp.html">Klapki</a>
        </div>
    </nav>

    <main>
      <!-- Pokaz slajdów -->
      <div class="pokaz">
        <button onclick="plus(-1)">&#10094;</button>

        <a href="aktualnosciJordany.php"><img class="pokazslajdow" src="img/slider/jord.jpg"  style="width:100%" ></a>
        <a href="aktualnosciCampusy.php"><img class="pokazslajdow" src="img/slider/elo.jpg" style="width:100%"></a>
        <a href="aktualnosciKlapki.php"><img class="pokazslajdow" src="img/slider/klapki.png" style="height:1000px" style="width:100%"></a>

        <button onclick="plus(1)">&#10095;</button>
      </div>
    
      <!-- Sekcja bestsellerów -->
      <section class="bestsellery">
        <h2>Nasze Bestsellery</h2>
        <div class="produkty">
          <a href="A1ForceBiale.php" class="produkt">
            <img src="img/Nike/AF1/AF1white.jpg" alt="Nike Air Force 1" />
            <p>Nike Air Force 1</p>
            <span>499 zł</span>
          </a>
          <a href="J1Mocha.php" class="produkt">
            <img src="img/Jordan/Mocha/Mocha1.jpeg" alt="Nike Air Jordan 1 Mocha" />
            <p>Nike Air Jordan 1 High Mocha</p>
            <span>1249 zł</span>
          </a>
          <a href="Campusy.php" class="produkt">
            <img src="img/Adidas/Campus/1.avif" alt="Adidas Campus 00s" />
            <p>Adidas Campus 00s</p>
            <span>529 zł</span>
          </a>
          <a href="Samba.php" class="produkt">
            <img src="img/Adidas/Samba/samba1.jpg" alt="Adidas Samba OG" />
            <p>Adidas Samba OG</p>
            <span>429 zł</span>
          </a>
        </div>
      </section>

      <!-- Dlaczego warto nas wybrać -->
      <section class="why-us">
        <h2>Dlaczego My?</h2>
        <ul>
          <li>✅ Darmowa dostawa od 300 zł</li>
          <li>✅ Tylko oryginalne produkty</li>
          <li>✅ 14 dni na zwrot</li>
        </ul>
      </section>
    
      <!-- Partnerzy -->
      <section class="partners">
        <h2>Nasi Partnerzy</h2>
        <div class="partner-logos">
          <img src="img/parrtnerzy/nike.png" alt="Nike">
          <img src="img/parrtnerzy/adidas.png" alt="Adidas">
          <img src="img/parrtnerzy/reebok.png" alt="reebok">
          <img src="img/parrtnerzy/under.png" alt="under">
        </div>
      </section>
    </main>

    <!-- Skrypt obsługujący pokaz slajdów -->
    <script>
      var indeks = 1;
      pok(indeks);

      function plus(n) {
          pok(indeks += n);
      }

      function pok(n) {
          var i;
          var x = document.getElementsByClassName("pokazslajdow");
          if (n > x.length) {indeks = 1}
          if (n < 1) {indeks = x.length}
          for (i = 0; i < x.length; i++) {
              x[i].style.display = "none";
          }
          x[indeks-1].style.display = "block";
      }
    </script>

  </div>

  <!-- Stopka -->
  <footer>
    <p>&copy; 2025 Sklep z Butami | kontakt@buty.pl</p>
  </footer>
</body>
</html>
