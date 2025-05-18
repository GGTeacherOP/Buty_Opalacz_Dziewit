

<?php
session_start();
include 'auth_utils.php';

$zalogowany = isset($_SESSION['username']);
$rola = $_SESSION['rola'] ?? 'gość';

$host = "localhost";
$uzytkownik_db = "root";
$haslo_db = "";
$nazwa_bazy = "buty";

$polaczenie = new mysqli($host, $uzytkownik_db, $haslo_db, $nazwa_bazy);

if ($polaczenie->connect_error) {
    die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error);
}

$product_id = 2; // Stałe ID produktu dla tej strony
$product_name = "Jordan 1 Mocha";
$product_price = 1249.00;
$product_image = "img/Jordan/Mocha/Mocha1.jpeg";

// Funkcja do pobierania opinii z bazy danych
function pobierz_opinie($polaczenie, $id_produktu) {
    $opinie = [];
    $sql = "SELECT ocena, komentarz, imie FROM opinie WHERE id_produktu = ?";
    $stmt = $polaczenie->prepare($sql);
    $stmt->bind_param("i", $id_produktu);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $opinie[] = $row;
    }
    $stmt->close();
    return $opinie;
}

$opinie_produktu = pobierz_opinie($polaczenie, $product_id);

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sklep z Butami – Jordan 1 Mocha</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="img/favi2.png" type="image/png">
    <style>
        .stars {
            cursor: pointer;
            font-size: 24px;
            color: lightgray;
        }

        .stars.selected {
            color: gold;
        }

        .review {
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
            padding-bottom: 10px;
        }

        #formularz-opinii {
            max-width: 600px;
            margin: 2rem auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            background-color: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        #formularz-opinii input,
        #formularz-opinii textarea {
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            background-color: #fafafa;
        }

        #formularz-opinii button {
            padding: 1rem;
            border: none;
            background-color: #222;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            border-radius: 8px;
        }

        #formularz-opinii button:hover {
            background-color: #000;
            font-size: 1.3rem;
            padding: 0.83rem;
        }
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

        <main class="product-page">
            <div class="product-container">
                <div class="gallery">
                    <img src="<?= htmlspecialchars($product_image) ?>" alt="<?= htmlspecialchars($product_name) ?>"
                         class="main-img" />
                    <div class="thumbnails">
                        <img src="img/Jordan/Mocha/Mocha1.jpeg" alt="Zdjęcie 1" />
                        <img src="img/Jordan/Mocha/Mocha2.jpeg" alt="Zdjęcie 2" />
                        <img src="img/Jordan/Mocha/Mocha3.jpeg" alt="Zdjęcie 3" />
                        <img src="img/Jordan/Mocha/Mocha4.jpeg" alt="Zdjęcie 4" />
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
            </form>

            <section class="opinie-produktu">
                <form id="formularz-opinii">
                    <h3>Dodaj swoją opinię: </h3>
                    <label>Ocena:</label>
                    <div id="gwiazdki">
                        <span data-value="1">★</span>
                        <span data-value="2">★</span>
                        <span data-value="3">★</span>
                        <span data-value="4">★</span>
                        <span data-value="5">★</span>
                    </div>


                    <label for="imie">Imię:</label>
                    <input type="text" id="imie" required>
                    <label for="opinia">Opinia:</label>
                    <textarea id="opinia" rows="4" required></textarea><br>
                    <button type="submit">Dodaj opinię</button>
                </form>
                <h2>Opinie: </h2>
                <?php if (!empty($opinie_produktu)): ?>
                    <?php foreach ($opinie_produktu as $opinia): ?>
                        <blockquote><?= str_repeat('⭐️', $opinia['ocena']) ?>
                            "<?= htmlspecialchars($opinia['komentarz']) ?>" – <?= htmlspecialchars($opinia['imie']) ?>
                        </blockquote><br>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Brak opinii dla tego produktu.</p>
                <?php endif; ?>

                <script>
                    const gwiazdki = document.querySelectorAll('#gwiazdki span');
                    let wybranaOcena = 0;

                    gwiazdki.forEach(star => {
                        star.style.cursor = 'pointer';
                        star.style.fontSize = '24px';

                        star.addEventListener('click', () => {
                            wybranaOcena = parseInt(star.dataset.value);
                            aktualizujGwiazdki();
                        });
                    });

                    function aktualizujGwiazdki() {
                        gwiazdki.forEach(star => {
                            if (parseInt(star.dataset.value) <= wybranaOcena) {
                                star.style.color = 'gold';
                            } else {
                                star.style.color = 'gray';
                            }
                        });
                    }

                    document.getElementById('formularz-opinii').addEventListener('submit', function (e) {
                        e.preventDefault();

                        const imie = document.getElementById('imie').value.trim();
                        const opinia = document.getElementById('opinia').value.trim();

                        if (wybranaOcena === 0 || !opinia || !imie) {
                            alert('Uzupełnij wszystkie pola i wybierz ocenę.');
                            return;
                        }

                        // Send data to the server
                        fetch('zapisz_opinie.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `id_produktu=<?= $product_id ?>&ocena=${wybranaOcena}&imie=${encodeURIComponent(imie)}&opinia=${encodeURIComponent(opinia)}`
                        })
                            .then(response => response.text())
                            .then(data => {
                                console.log(data);
                                // Handle response if needed (e.g., show a success message)
                                // Po dodaniu opinii, odśwież stronę, aby wyświetlić nową opinię
                                location.reload();
                            })
                            .catch(error => console.error('Error:', error));
                    });
                </script>

            </section>

        </main>

        <footer class="footer">
            <div class="footer-container">
                <div class="footer-column">
                    <h3>Kontakt</h3>
                    <p>Buty Opalacz Dziewit</p>
                    <p>ul. Kwiatowa 30, Mielec</p>
                    <p>Tel: <a href="tel:+48123456789"> +48 123 456 789</a></p>
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
