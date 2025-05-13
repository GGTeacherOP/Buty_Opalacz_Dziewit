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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
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

        <a href="aktualnosciCampusy.php"><img class="pokazslajdow" src="img/slider/elo.jpg"  style="width:100%" ></a>
        <a href="aktualnosciJordany.php"><img class="pokazslajdow" src="img/slider/jord.jpg" style="width:100%"></a>
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
  <h2>Dlaczego warto wybrać nas?</h2>
  <div class="why-cards">
    <div class="why-card">
      <i class="fas fa-shipping-fast"></i>
      <h3>Darmowa dostawa</h3>
      <p>Przy zamówieniach od 300 zł, przesyłka gratis!</p>
    </div>
    <div class="why-card">
      <i class="fas fa-certificate"></i>
      <h3>Oryginalne produkty</h3>
      <p>100% autentyczne, markowe obuwie od sprawdzonych dostawców.</p>
    </div>
    <div class="why-card">
      <i class="fas fa-undo-alt"></i>
      <h3>14 dni na zwrot</h3>
      <p>Bezproblemowy zwrot towaru do 14 dni od zakupu.</p>
    </div>
  </div>
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
 <footer class="footer">
  <div class="footer-container">
    <div class="footer-column">
      <h3>Kontakt</h3>
      <p>Buty Opalacz Dziewit</p>
      <p>ul. Kwiatowa 30,  Mielec </p>
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

</body>
</html>
