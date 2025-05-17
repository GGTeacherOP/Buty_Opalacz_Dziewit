<?php
session_start(); // Uruchomienie sesji
include 'auth_utils.php';
$zalogowany = isset($_SESSION['username']);
$rola = $_SESSION['rola'] ?? 'gość';  // Domyślnie 'gość' dla niezalogowanych
// Dane do połączenia z bazą danych
$host = "localhost";       // Adres serwera bazy danych
$uzytkownik_db = "root";        // Nazwa użytkownika bazy danych
$haslo_db = "";            // Hasło do bazy danych
$nazwa_bazy = "buty";         // Nazwa bazy danych

// Połączenie z bazą danych przy użyciu mysqli
$polaczenie = new mysqli($host, $uzytkownik_db, $haslo_db, $nazwa_bazy);

// Sprawdzenie, czy połączenie się udało
if ($polaczenie->connect_error) {
    die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error); // Jeśli wystąpił błąd, skrypt zostaje zatrzymany i wyświetla komunikat
}

$product_id = 15; 
$product_name = "Vans Sk8-Hi Biało-Czarne ";
$product_price = 429.00;
$product_image = "img/VANS/VansSk8/VansSk81.avif"

?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sklep z Butami – Adidas Campus 00s</title>
  <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="icon" href="img/favi2.png" type="image/png">
</head>
<body>
    <div class="wrapper">
    <header>
        <a href="index.php">Strona Główna</a>
            <a href="sklep.php" class="active">Sklep</a>
            <a href="koszyk.php">Koszyk</a>
            <a href="kontakt.php">Kontakt</a>
            <a href="opinie.php">Opinie</a>
            <a href="aktualnosci.php">Aktualności</a>
                   <?php if ($zalogowany): ?>
                <span style="float:right; margin-left: 10px; color:#007bff; font-weight: bold;">
                    Witaj, <?= htmlspecialchars($_SESSION['username']) ?>! (<?= $rola ?>)
                </span>
                <a href="logout.php" style="float:right;" class="zg">Wyloguj</a>
            <?php else: ?>
                <a href="login.php" class="zg">Zaloguj</a>
                <a href="register.php" class="zg">Zarejestruj</a>
            <?php endif; ?>

            <?php if (czy_ma_role(['kierownik', 'admin', 'szef', 'Pracownik sklepu'])): ?>
                <a href="produkty.php">Panel Produktów i Zamówien</a>
            <?php endif; ?>

            <?php if (czy_ma_role(['kierownik', 'admin', 'szef'])): ?>
                <a href="panel_pracownikow.php">Panel Pracowników</a>
            <?php endif; ?>

            <?php if (czy_ma_role('admin', 'szef')): ?>
                <a href="panel_admina.php">Panel Admina</a>
            <?php endif; ?>

             <?php if (czy_ma_role('szef')): ?>
                <a href="panel_szef.php">Panel Szefa</a>
            <?php endif; ?>
    </header>

  <main class="product-page">
            <div class="product-container">
                <div class="gallery">
                    <img src="<?= htmlspecialchars($product_image) ?>" alt="<?= htmlspecialchars($product_name) ?>"
                        class="main-img" />
                    <div class="thumbnails">
          <img src="img/VANS/VansSk8/VansSk81.avif" alt="Zdjęcie 1" />
          <img src="img/VANS/VansSk8/VansSk82.avif" alt="Zdjęcie 2" />
          <img src="img/VANS/VansSk8/VansSk83.avif" alt="Zdjęcie 3" />
          <img src="img/VANS/VansSk8/VansSk84.avif" alt="Zdjęcie 4" />
        </div>
      </div>
           <div class="product-details">
                    <h1><?= htmlspecialchars($product_name) ?></h1>
                    <p class="price"><?= number_format($product_price, 2) ?> zł</p>
                    <p>Stylowe sneakersy</p>

                    <form action="koszyk.php" method="POST">
                        <label>Rozmiar:
                            <select id="product-size" name="rozmiar" required>
                                <option value="">Wybierz rozmiar</option>
                                <option value="38">38</option>
                                <option value="39">39</option>
                                <option value="40">40</option>
                                <option value="41">41</option>
                                <option value="42">42</option>
                                <option value="43">43</option>
                                <option value="44">44</option>
                            </select>
                        </label>

                        <div class="buttons">
                            <?php if ($zalogowany): ?>
                                <input type="hidden" name="id_produktu" value="<?= $product_id ?>">
                                <input type="hidden" name="nazwa" value="<?= htmlspecialchars($product_name) ?>">
                                <input type="hidden" name="cena" value="<?= $product_price ?>">
                                <input type="hidden" name="zdjecie" value="<?= htmlspecialchars($product_image) ?>">
                                <input type="hidden" name="dodaj_do_koszyka" value="1">
                                <button type="submit" class="buy-now add-to-cart-btn">Dodaj do koszyka</button>
                            <?php else: ?>
                                <p>Musisz być <a href="login.php">zalogowany</a>, aby dodać produkt do koszyka.</p>
                            <?php endif; ?>

                      
                    </div>
                </form>
            </div>
        </div>

    <section class="opinie-produktu">
      <h2>Opinie</h2>
      <blockquote>⭐️⭐️⭐️⭐️⭐️ "Piękne! Zachęcam do zakupu!" – Ewelina</blockquote>
      <blockquote>⭐️⭐️⭐️⭐️ "Szybka dostawa, buty przyszły w idealnym stanie." – Wojciech</blockquote>
    </section>
  </main>
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


  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const thumbnails = document.querySelectorAll(".thumbnails img");
      const mainImg = document.querySelector(".main-img");
  
      thumbnails.forEach((thumb) => {
        thumb.addEventListener("click", () => {
          if (mainImg && thumb.src) {
            mainImg.src = thumb.src;
          }
        });
      });
    });
  </script>
  
</body>
</html>