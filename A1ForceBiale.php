<?php
session_start();
$zalogowany = isset($_SESSION['username']);

$host = "localhost";
$uzytkownik_db = "root";
$haslo_db = "";
$nazwa_bazy = "buty";

$polaczenie = new mysqli($host, $uzytkownik_db, $haslo_db, $nazwa_bazy);

if ($polaczenie->connect_error) {
    die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error);
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sklep z Butami – Nike Air Force 1</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="img/favi2.png" type="image/png">
    <style>
        /* Style CSS pozostają bez zmian */
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
                    Witaj, <?= htmlspecialchars($_SESSION['username']) ?>!
                </span>
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
                    <select id="product-size" name="rozmiar">
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
                  <form action="koszyk.php" method="POST">
                    <input type="hidden" name="id_produktu" value="1">
                    <input type="hidden" name="nazwa" value="Nike Air Force 1 Białe">
                    <input type="hidden" name="cena" value="499">
                    <?php
                    $sciezka_do_obrazka = "img/Nike/AF1/AF1white.jpg";  
                    ?>
                    <input type="hidden" name="zdjecie" value="<?= htmlspecialchars($sciezka_do_obrazka) ?>">
                    <input type="hidden" name="rozmiar" id="product-size-add-to-cart" value="">
                    <input type="hidden" name="dodaj_do_koszyka" value="1">  <button type="submit" class="buy-now add-to-cart-btn">Dodaj do koszyka</button>
                </form>
               <form action="zapis_zamowienia.php" method="POST">
    <input type="hidden" name="potwierdz_zamowienie" value="1">
    <input type="hidden" name="nazwa[]" value="Nike Air Force 1 Białe">
    <input type="hidden" name="cena[]" value="499">
    <input type="hidden" name="ilosc[]" value="1">
    <input type="hidden" name="rozmiar" id="product-size-buy-now" value="">
    <?php if ($zalogowany && isset($_SESSION['id_uzytkownika'])): ?>
        <input type="hidden" name="id_uzytkownika" value="<?= $_SESSION['id_uzytkownika'] ?>">
    <?php endif; ?>
    <button type="submit" class="buy-now buy-now-btn">Kup teraz</button>
</form>

                    <script>
                        // Skrypt JavaScript do obsługi wyboru rozmiaru
                        const productSize = document.getElementById('product-size');
                        const productSizeAddToCart = document.getElementById('product-size-add-to-cart');
                        const productSizeBuyNow = document.getElementById('product-size-buy-now');
                        const addToCartButton = document.querySelector('.add-to-cart-btn');
                        const buyNowButton = document.querySelector('.buy-now-btn');

                        function validateSize(event) {
                            if (productSize.value === '') {
                                alert('Wybierz rozmiar!');
                                event.preventDefault(); // Zatrzymaj wysyłanie formularza
                                return false;
                            }
                            return true;
                        }

                        addToCartButton.addEventListener('click', function (event) {
                            if (validateSize(event)) {
                                productSizeAddToCart.value = productSize.value;
                            }
                        });

                        buyNowButton.addEventListener('click', function (event) {
                            if (validateSize(event)) {
                                productSizeBuyNow.value = productSize.value;
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