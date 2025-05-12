<?php
session_start(); // Uruchomienie sesji
$zalogowany = isset($_SESSION['username']); // Sprawdzenie, czy użytkownik jest zalogowany
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


?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sklep z Butami – Jordan 4 Military Black</title>
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
        <img src="img/Jordan/Military/Military1.jpg" alt="Jordan 4 Military Black" class="main-img" />
        <div class="thumbnails">
          <img src="img/Jordan/Military/Military1.jpg" alt="Zdjęcie 1" />
          <img src="img/Jordan/Military/Military2.jpg" alt="Zdjęcie 2" />
          <img src="img/Jordan/Military/Military3.jpg" alt="Zdjęcie 3" />
          <img src="img/Jordan/Military/Military4.jpg" alt="Zdjęcie 4" />
        </div>
      </div>
      <div class="product-details">
        <h1>Jordan 4 Military Black</h1>
        <p class="price">1399 zł</p>
        <p>Stylowe sneakersy</p>

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
  <form action="koszyk.php" method="POST">
    <input type="hidden" name="nazwa" value="Jordan 4 Military Black">
    <input type="hidden" name="cena" value="1399">
    <input type="hidden" name="zdjecie" value="img/Jordan/Military/Military1.jpg">
    <button type="submit" class="buy-now">Dodaj do koszyka</button>
  </form>
   <form action="zapis_zamowienia.php" method="POST">
    <input type="hidden" name="nazwa" value="Jordan 4 Military Black">
    <input type="hidden" name="cena" value="1399">
    <input type="hidden" name="zdjecie" value="img/Jordan/Military/Military1.jpg">
    <input type="hidden" name="rozmiar" id="product-size" value=""> <?php if ($zalogowany && isset($_SESSION['id_uzytkownika'])): ?>
        <input type="hidden" name="id_uzytkownika" value="<?= $_SESSION['id_uzytkownika'] ?>">
    <?php endif; ?>
    <button type="submit" class="buy-now">Kup teraz</button>
  </form>

<script>
    // Skrypt JavaScript do obsługi wyboru rozmiaru przed zakupem
    document.querySelector('form[action="zapis_zamowienia.php"] .buy-now').addEventListener('click', function(event) {
        var rozmiar = document.getElementById('product-size').value; // Pobranie wybranego rozmiaru
        if (rozmiar === '') {
            alert('Wybierz rozmiar!');  // Wyświetlenie alertu, jeśli nie wybrano rozmiaru
            event.preventDefault(); // Zatrzymaj wysyłanie formularza
        } else {
            document.querySelector('form[action="zapis_zamowienia.php"] input[name="rozmiar"]').value = rozmiar; // Wypełnienie ukrytego pola rozmiar
        }
    });
</script>
</div>
      </div>
    </div>

    <section class="opinie-produktu">
      <h2>Opinie</h2>
      <blockquote>⭐️⭐️⭐️⭐️ "Dobre buty." – Damian</blockquote>
      <blockquote>⭐️⭐️⭐️⭐️ "Szybka dostawa, buty przyszły w idealnym stanie." – Miłosz</blockquote>
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