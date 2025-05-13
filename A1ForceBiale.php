<?php
session_start(); // Rozpoczęcie sesji, aby móc korzystać ze zmiennych sesyjnych
$zalogowany = isset($_SESSION['username']); // Sprawdzenie, czy użytkownik jest zalogowany (czy istnieje zmienna sesyjna 'username')

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
    <title>Sklep z Butami – Nike Air Force 1</title>
    <link rel="stylesheet" href="css/style.css"/>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="img/favi2.png" type="image/png">
    <style>
       
    

    </style>
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
                Witaj, <?= htmlspecialchars($_SESSION['username']) ?>!  </span>
            <a href="logout.php" style="float:right;" class="zg">Wyloguj</a>
        <?php else: ?>
            <a href="login.php" class="zg">Zaloguj</a>
            <a href="register.php" class="zg">Zarejestruj</a>  
        <?php endif; ?>
    </header>

  <main class="product-page">
    <div class="product-container">
      <div class="gallery">
        <img src="img/Nike/AF1/AF1white.jpg" alt="Nike Air Force 1" class="main-img" />
        <div class="thumbnails">
          <img src="img/Nike/AF1/AF1white.jpg" alt="Zdjęcie 1" />
          <img src="img/Nike/AF1/AF1white2.jpg" alt="Zdjęcie 2" />
          <img src="img/Nike/AF1/AF1white3.jpg" alt="Zdjęcie 3" />
          <img src="img/Nike/AF1/AF1white4.jpg" alt="Zdjęcie 4" />
        </div>
      </div>
      <div class="product-details">
        <h1>Nike Air Force 1</h1>
        <p class="price">499 zł</p>
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
    <input type="hidden" name="nazwa" value="Nike Air Force 1 Białe">
    <input type="hidden" name="cena" value="499">
    <input type="hidden" name="zdjecie" value="img/Nike/AF1/AF1white.jpg">
    <button type="submit" class="buy-now">Dodaj do koszyka</button>
  </form>
  <form action="zapis_zamowienia.php" method="POST">
    <input type="hidden" name="nazwa" value="Nike Air Force 1 Białe">
    <input type="hidden" name="cena" value="499">
    <input type="hidden" name="zdjecie" value="img/Nike/AF1/AF1white.jpg">
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
      <blockquote>⭐️⭐️⭐️⭐️⭐️ "Cudnie wyglądają, bardzo polecam!" – Agnieszka</blockquote>
      <blockquote>⭐️⭐️⭐️⭐️ "But dotarł w idealnym stanie, dostawa natychmiastowa." – Elżbieta</blockquote>
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
    // Skrypt JavaScript do obsługi zmiany głównego zdjęcia po kliknięciu miniaturki
    document.addEventListener("DOMContentLoaded", () => {
      const thumbnails = document.querySelectorAll(".thumbnails img");  // Pobranie wszystkich miniaturek
      const mainImg = document.querySelector(".main-img");          // Pobranie głównego zdjęcia
  
      thumbnails.forEach((thumb) => {
        thumb.addEventListener("click", () => {
          if (mainImg && thumb.src) {
            mainImg.src = thumb.src;  // Zmiana źródła głównego zdjęcia na źródło klikniętej miniaturki
          }
        });
      });
    });
  </script>
  
</body>
</html>