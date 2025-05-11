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

// Sprawdzenie, czy użytkownik jest zalogowany i czy istnieje ID użytkownika w sesji
if ($zalogowany && isset($_SESSION['id_uzytkownika'])) {
    $id_uzytkownika = $_SESSION['id_uzytkownika']; // Pobranie ID użytkownika z sesji
} else if ($zalogowany) {
    // Użytkownik zalogowany, ale brak id_uzytkownika w sesji - BŁĄD!
    // Wyświetlenie komunikatu błędu i przekierowanie na stronę główną
    echo "<script>alert('Błąd: Brak ID użytkownika w sesji. Skontaktuj się z administratorem.'); window.location.href='index.php';</script>";
    exit; // Zakończenie skryptu
} else {
    // Użytkownik niezalogowany
    // Wyświetlenie komunikatu i przekierowanie na stronę logowania
    echo "<script>alert('Musisz być zalogowany, aby dokonać zakupu.'); window.location.href='login.php';</script>";
    exit; // Zakończenie skryptu
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sklep z Butami – Jordan Klapki Białe</title>
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
        <img src="img/Jordan/KlapkiBiale/1.avif" alt="Jordan Klapki Biale" class="main-img" />
        <div class="thumbnails">
          <img src="img/Jordan/KlapkiBiale/1.avif" alt="Zdjęcie 1" />
          <img src="img/Jordan/KlapkiBiale/2.avif" alt="Zdjęcie 2" />
          <img src="img/Jordan/KlapkiBiale/3.avif" alt="Zdjęcie 3" />
          <img src="img/Jordan/KlapkiBiale/4.avif" alt="Zdjęcie 4" />
        </div>
      </div>
      <div class="product-details">
        <h1>Jordan Klapki Białe</h1>
        <p class="price">250 zł</p>
        <p>Stylowe klapki</p>

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
    <input type="hidden" name="nazwa" value="Jordan Klapki Białe">
    <input type="hidden" name="cena" value="250">
    <input type="hidden" name="zdjecie" value="img/Jordan/KlapkiBiale/1.avif">
    <button type="submit" class="buy-now">Dodaj do koszyka</button>
  </form>
  <button class="buy-now">Kup teraz</button>
</div>
      </div>
    </div>

    <section class="opinie-produktu">
      <h2>Opinie</h2>
      <blockquote>⭐️⭐️⭐️⭐️⭐️ "Świetnie wyglądają, wygoda przede wszystkim!" – Wiktoria</blockquote>
      <blockquote>⭐️⭐️⭐️ "Dostawa przyszła troche opóźniona a tak to klapki w dobrym stanie" – Kuba</blockquote>
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