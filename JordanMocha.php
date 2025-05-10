<?php
session_start(); // Uruchomienie sesji
$zalogowany = isset($_SESSION['username']); // Sprawdzenie, czy użytkownik jest zalogowany
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sklep z Butami – Jordan 1 Mocha</title>
  <link rel="stylesheet" href="css/style.css" />
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

  <main class="product-page">
    <div class="product-container">
      <div class="gallery">
        <img src="img/Jordan/Mocha/Mocha1.jpeg" alt="Jordan 1 Mocha" class="main-img" />
        <div class="thumbnails">
          <img src="img/Jordan/Mocha/Mocha1.jpeg" alt="Zdjęcie 1" />
          <img src="img/Jordan/Mocha/Mocha2.jpeg" alt="Zdjęcie 2" />
          <img src="img/Jordan/Mocha/Mocha3.jpeg" alt="Zdjęcie 3" />
          <img src="img/Jordan/Mocha/Mocha4.jpeg" alt="Zdjęcie 4" />
        </div>
      </div>
      <div class="product-details">
        <h1>Jordan 1 Mocha</h1>
        <p class="price">1249 zł</p>
        <p>Stylowe buty</p>

        <label>Rozmiar:
          <select id="product-size">
            <option value="">Wybierz rozmiar</option>
            <option>38</option>
            <option>39</option>
            <option>40</option>
            <option>41</option>
            <option>42</option>
            <option>43</option>
            <option>44</option>
          </select>
        </label>

      

        <div class="buttons">
          <button class="add-to-cart" data-name="Jordan 1 Mocha" data-price="1249">Dodaj do koszyka</button>
          <button class="buy-now">Kup teraz</button>
        </div>
      </div>
    </div>

    <section class="opinie-produktu">
      <h2>Opinie</h2>
      <blockquote>⭐️⭐️⭐️⭐️⭐️ "Dobrze wykonane, piękna kolorystyka." – Tomasz</blockquote>
      <blockquote>⭐️⭐️⭐️⭐️ "Szybka dostawa, buty przyszły w idealnym stanie." – Oliwier</blockquote>
    </section>
  </main>
</div>
  <footer class="footer">
    <p>&copy; 2025 Sklep z Butami | kontakt@buty.pl</p>
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