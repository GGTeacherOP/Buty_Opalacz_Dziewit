<?php
// Uruchomienie mechanizmu sesji w PHP. Pozwala na przechowywanie danych użytkownika między różnymi żądaniami.
session_start();
// Dołączenie zewnętrznego pliku 'auth_utils.php'. Ten plik prawdopodobnie zawiera funkcje związane z autentykacją i autoryzacją użytkowników.
include 'auth_utils.php';
// Sprawdzenie, czy w sesji istnieje zmienna 'username'. Jeśli tak, oznacza to, że użytkownik jest zalogowany. Wynik przypisywany jest do zmiennej $zalogowany.
$zalogowany = isset($_SESSION['username']);
// Pobranie roli użytkownika z sesji. Jeśli zmienna 'rola' nie istnieje (np. dla niezalogowanych), domyślnie ustawiana jest wartość 'gość'.
$rola = $_SESSION['rola'] ?? 'gość';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <title>Sklep z Butami – Strona główna</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="img/favi2.png" type="image/png">
    <style>
        .news-section {
            max-width: 1200px; /* Maksymalna szerokość sekcji aktualności. */
            margin: 2rem auto; /* Górny i dolny margines 2rem, automatyczne marginesy po bokach (wyśrodkowanie). */
            padding: 0 1rem; /* Wewnętrzny padding po bokach 1rem. */
            font-size: 25px; /* Domyślny rozmiar czcionki w sekcji. */
        }

        .news-title {
            text-align: center; /* Wyśrodkowanie tekstu tytułu. */
            font-size: 2.2rem; /* Większy rozmiar czcionki dla tytułu. */
            margin-bottom: 2rem; /* Dolny margines tytułu. */
        }



        .news-card {
            background-color: #fff; /* Białe tło karty aktualności. */
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); /* Delikatny cień pod kartą. */
            padding: 0; /* Brak wewnętrznego paddingu karty. */
            overflow: hidden; /* Ukrycie zawartości wychodzącej poza granice karty. */
            transition: transform 0.3s; /* Płynna transformacja przy najechaniu kursorem. */
            border-radius: 20px; /* Zaokrąglone rogi karty. */
        }

        .news-card:hover {
            transform: translateY(-5px); /* Przesunięcie karty o 5px w górę przy najechaniu kursorem. */
        }

        .news-card img {
            width: 200px; /* Szerokość obrazka w karcie. */
            height: 250px; /* Wysokość obrazka w karcie. */
            object-fit: cover; /* Skalowanie i przycinanie obrazka, aby wypełnił obszar. */
            float: left; /* Umieszczenie obrazka po lewej stronie tekstu. */
        }

        .news-card-content {
            padding: 1rem; /* Wewnętrzny padding dla treści karty. */
        }

        .news-card-content h3 {
            margin-bottom: 0.5rem; /* Dolny margines tytułu w treści karty. */
            color: #111; /* Ciemny kolor tekstu tytułu. */
        }

        .news-card-content p {
            font-size: 1rem; /* Rozmiar czcionki paragrafu w treści karty. */
            color: #444; /* Ciemnoszary kolor tekstu paragrafu. */
        }

        .news-card-content .date {
            display: block; /* Wyświetlanie daty jako element blokowy. */
            margin-top: 1rem; /* Górny margines daty. */
            font-size: 0.85rem; /* Mniejszy rozmiar czcionki daty. */
            color: #999; /* Jasnoszary kolor tekstu daty. */
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
                <p>     Przedstawiamy najnowszą kolekcję butów Adidas Campus – klasyka w nowoczesnym wydaniu! Idealne na co dzień, wykonane z wysokiej jakości materiałów, zapewniają maksymalny komfort i styl. Dostępne w wielu wariantach kolorystycznych.</p>
                <p class="date">         2 maja 2025</p>
            </div>
        </a>
            </div>

    <br>
    <a href="aktualnosciJordany.php" style="text-decoration: none; color: inherit; display: block;">
        <div class="news-card">
            <img src="img/aktualnosci/3.png" class="nowosc" alt="Buty Jordan">
            <div class="news-card-content">
                <h3>     🔥 Nowa kolekcja Jordan & Nike</h3>
                <p>     Do oferty trafiły limitowane modele Air Jordan 1, Air Jordan 1 High oraz nowości od Nike! Wyjątkowy design, precyzja wykonania i wygoda – to cechy, które wyróżniają tę kolekcję. Idealne dla fanów streetwearu i sneakerheadów.</p>
                <p class="date"> 28 kwietnia 2025</p>
            </div>
        </div>
    </a>

    <br>
    <a href="aktualnosciKlapki.php" style="text-decoration: none; color: inherit; display: block;">
        <div class="news-card">
            <img src="img/aktualnosci/5.png" alt="klapki">
            <div class="news-card-content">
                <h3>     👟 Nowa kolekcja klapkow</h3>
                <p>     Nowa kolekcja Klapków! Obczaj co nowego czeka na ciebie! Klapki z różnych marek - NIKE, ADIDAS i JORDAN w bardzo korzystnych cenach zobaczcie sami!!!</p>
                <span class="date"> 29 kwietnia 2025</span>
            </div>
        </div>
        </a>
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
    </div>
</body>
</html>




    
  