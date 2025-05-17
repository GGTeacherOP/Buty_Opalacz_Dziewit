<?php
session_start();
include 'auth_utils.php';
$zalogowany = isset($_SESSION['username']);
$rola = $_SESSION['rola'] ?? 'gość';  // Domyślnie 'gość' dla niezalogowanych
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
            <a href="index.php" class="active">Strona Główna</a>
            <a href="sklep.php">Sklep</a>
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

       <nav>
    <p class="prz">Buty</p>
    <div class="zbior">
        <a href="sklep.php?type=Sneakersy">Sneakersy</a>
        <a href="sklep.php?type=Trampki">Trampki</a>
        <a href="sklep.php?type=Biegania">Buty do biegania</a>
        <a href="sklep.php?type=Treningowe">Buty treningowe</a>
        <a href="sklep.php?type=Klapki">Klapki</a>
    </div>
</nav>

        <main>
            <div class="pokaz">
                <button onclick="plus(-1)">&#10094;</button>

                <a href="aktualnosciCampusy.php"><img class="pokazslajdow" src="img/slider/elo.jpg" style="width:100%"></a>
                <a href="aktualnosciJordany.php"><img class="pokazslajdow" src="img/slider/jord.jpg" style="width:100%"></a>
                <a href="aktualnosciKlapki.php"><img class="pokazslajdow" src="img/slider/klapki.jpg" style="width:100%"></a>

                <button onclick="plus(1)">&#10095;</button>
            </div>

            <section class="bestsellery">
                <h2>Nasze Bestsellery</h2>
                <div class="produkty-grid">
                    <div class="produkt-card">
                        <img src="img/Nike/AF1/AF1white.jpg" alt="Nike Air Force 1" />
                        <h3>Nike Air Force 1</h3>
                        <p class="cena">499 zł</p>
                        <a href="A1ForceBiale.php" class="btn-zobacz">Zobacz</a>
                    </div>

                    <div class="produkt-card">
                        <img src="img/Jordan/Mocha/Mocha1.jpeg" alt="Nike Air Jordan 1 Mocha" />
                        <h3>Jordan 1 High Mocha</h3>
                        <p class="cena">1249 zł</p>
                        <a href="J1Mocha.php" class="btn-zobacz">Zobacz</a>
                    </div>

                    <div class="produkt-card">
                        <img src="img/Adidas/Campus/1.avif" alt="Adidas Campus 00s" />
                        <h3>Adidas Campus 00s</h3>
                        <p class="cena">529 zł</p>
                        <a href="Campusy2.php" class="btn-zobacz">Zobacz</a>
                    </div>

                    <div class="produkt-card">
                        <img src="img/Adidas/ForumBlack/Forum1.jpg" alt="Adidas Forum Low" />
                        <h3>Adidas Forum Low</h3>
                        <p class="cena">499 zł</p>
                        <a href="AdidasFor.php" class="btn-zobacz">Zobacz</a>
                    </div>
                </div>
            </section>


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

        <script>
            var indeks = 1;
            pok(indeks);

            function plus(n) {
                pok(indeks += n);
            }

            function pok(n) {
                var i;
                var x = document.getElementsByClassName("pokazslajdow");
                if (n > x.length) {
                    indeks = 1
                }
                if (n < 1) {
                    indeks = x.length
                }
                for (i = 0; i < x.length; i++) {
                    x[i].style.display = "none";
                }
                x[indeks - 1].style.display = "block";
            }
        </script>

    </div>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <h3>Kontakt</h3>
                <p>Buty Opalacz Dziewit</p>
                <p>ul. Kwiatowa 30, Mielec </p>
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