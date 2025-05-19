<?php
// Rozpoczęcie sesji, umożliwiającej dostęp do zmiennych sesyjnych
session_start();
// Załączenie pliku z funkcjami uwierzytelniającymi
include 'auth_utils.php';

// Sprawdzenie, czy użytkownik jest zalogowany poprzez istnienie zmiennej sesyjnej 'username'
$zalogowany = isset($_SESSION['username']);
// Pobranie roli użytkownika z sesji, jeśli istnieje, w przeciwnym razie ustawienie domyślnej roli 'gość'
$rola = $_SESSION['rola'] ?? 'gość';

// Dane do połączenia z bazą danych
$host = "localhost";
$uzytkownik_db = "root";
$haslo_db = "";
$nazwa_bazy = "buty";

// Utworzenie nowego połączenia z bazą danych MySQL
$polaczenie = new mysqli($host, $uzytkownik_db, $haslo_db, $nazwa_bazy);

// Sprawdzenie, czy wystąpił błąd podczas połączenia z bazą danych
if ($polaczenie->connect_error) {
    // Jeśli połączenie nie powiodło się, wyświetlenie komunikatu o błędzie i zakończenie skryptu
    die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error);
}

// Definicja stałych informacji o produkcie (dla tej konkretnej strony produktu)
$product_id = 2; // Stałe ID produktu w bazie danych
$product_name = "Jordan 1 Mocha"; // Nazwa produktu
$product_price = 1249.00; // Cena produktu
$product_image = "img/Jordan/Mocha/Mocha1.jpeg"; // Ścieżka do głównego zdjęcia produktu

// Funkcja do pobierania opinii o produkcie z bazy danych
// Przyjmuje jako argument połączenie z bazą danych i ID produktu
function pobierz_opinie($polaczenie, $id_produktu) {
    $opinie = []; // Inicjalizacja pustej tablicy na opinie
    // Zapytanie SQL do pobrania oceny, komentarza i imienia autora opinii dla danego ID produktu
    $sql = "SELECT ocena, komentarz, imie FROM opinie WHERE id_produktu = ?";
    // Przygotowanie zapytania SQL do zabezpieczenia przed SQL injection
    $stmt = $polaczenie->prepare($sql);
    // Powiązanie parametru ID produktu z przygotowanym zapytaniem
    $stmt->bind_param("i", $id_produktu);
    // Wykonanie przygotowanego zapytania
    $stmt->execute();
    // Pobranie wyników zapytania
    $result = $stmt->get_result();

    // Iteracja po każdym wierszu wynikowym (każdej opinii)
    while ($row = $result->fetch_assoc()) {
        // Dodanie pobranego wiersza (opinii) do tablicy $opinie
        $opinie[] = $row;
    }
    // Zamknięcie przygotowanego zapytania
    $stmt->close();
    // Zwrócenie tablicy zawierającej wszystkie opinie dla danego produktu
    return $opinie;
}

// Wywołanie funkcji pobierz_opinie, aby uzyskać opinie dla aktualnego produktu
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
        /* Style CSS dla elementów związanych z opiniami */
        .stars {
            cursor: pointer; /* Kursor wskazujący, że element jest klikalny */
            font-size: 24px; /* Rozmiar ikon gwiazdek */
            color: lightgray; /* Domyślny kolor nieaktywnych gwiazdek */
        }

        .stars.selected {
            color: gold; /* Kolor wybranych gwiazdek */
        }

        .review {
            border-bottom: 1px solid #ccc; /* Linia oddzielająca opinie */
            margin-bottom: 10px; /* Dolny margines opinii */
            padding-bottom: 10px; /* Dolne wypełnienie opinii */
        }

        #formularz-opinii {
            max-width: 600px; /* Maksymalna szerokość formularza opinii */
            margin: 2rem auto; /* Wyśrodkowanie formularza i dodanie marginesów */
            display: flex; /* Użycie flexbox do układania elementów formularza */
            flex-direction: column; /* Ułożenie elementów formularza w kolumnie */
            gap: 1rem; /* Odstępy między elementami formularza */
            background-color: #fff; /* Białe tło formularza */
            padding: 2rem; /* Wewnętrzne odstępy w formularzu */
            border-radius: 15px; /* Zaokrąglone rogi formularza */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Lekki cień pod formularzem */
        }

        #formularz-opinii input,
        #formularz-opinii textarea {
            padding: 1rem; /* Wewnętrzne odstępy w polach input i textarea */
            border: 1px solid #ccc; /* Obramowanie pól */
            border-radius: 8px; /* Zaokrąglone rogi pól */
            font-size: 1rem; /* Rozmiar czcionki w polach */
            background-color: #fafafa; /* Jasnoszare tło pól */
        }

        #formularz-opinii button {
            padding: 1rem; /* Wewnętrzne odstępy w przycisku */
            border: none; /* Brak obramowania przycisku */
            background-color: #222; /* Ciemne tło przycisku */
            color: white; /* Biały tekst przycisku */
            font-size: 1rem; /* Rozmiar czcionki przycisku */
            font-weight: bold; /* Pogrubiony tekst przycisku */
            cursor: pointer; /* Kursor wskazujący, że element jest klikalny */
            border-radius: 8px; /* Zaokrąglone rogi przycisku */
        }

        #formularz-opinii button:hover {
            background-color: #000; /* Jeszcze ciemniejsze tło przy najechaniu kursorem */
            font-size: 1.3rem; /* Nieznaczne powiększenie tekstu przy najechaniu */
            padding: 0.83rem; /* Dostosowanie paddingu przy powiększeniu tekstu */
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
                    // Skrypt JavaScript do obsługi interakcji z gwiazdkami oceny i formularzem opinii
                    const gwiazdki = document.querySelectorAll('#gwiazdki span');
                    let wybranaOcena = 0; // Zmienna przechowująca wybraną ocenę

                    // Dodanie event listenerów do każdej gwiazdki
                    gwiazdki.forEach(star => {
                        star.style.cursor = 'pointer'; // Ustawienie kursora na pointer
                        star.style.fontSize = '24px'; // Ustawienie rozmiaru czcionki

                        star.addEventListener('click', () => {
                            wybranaOcena = parseInt(star.dataset.value); // Pobranie wartości oceny z atrybutu data-value
                            aktualizujGwiazdki(); // Wywołanie funkcji aktualizującej wygląd gwiazdek
                        });
                    });

                    // Funkcja aktualizująca kolor gwiazdek na podstawie wybranej oceny
                    function aktualizujGwiazdki() {
                        gwiazdki.forEach(star => {
                            if (parseInt(star.dataset.value) <= wybranaOcena) {
                                star.style.color = 'gold'; // Zmiana koloru na złoty dla wybranych i niższych gwiazdek
                            } else {
                                star.style.color = 'gray'; // Zmiana koloru na szary dla wyższych gwiazdek
                            }
                        });
                    }

                    // Obsługa submit formularza opinii
                    document.getElementById('formularz-opinii').addEventListener('submit', function (e) {
                        e.preventDefault(); // Zapobieganie domyślnej akcji submit

                        const imie = document.getElementById('imie').value.trim(); // Pobranie i oczyszczenie wartości imienia
                        const opinia = document.getElementById('opinia').value.trim(); // Pobranie i oczyszczenie treści opinii

                        // Walidacja pól formularza
                        if (wybranaOcena === 0 || !opinia || !imie) {
                            alert('Uzupełnij wszystkie pola i wybierz ocenę.');
                            return;
                        }

                        // Wysyłanie danych opinii na serwer za pomocą fetch API
                        fetch('zapisz_opinie.php', {
                            method: 'POST', // Metoda POST do wysyłania danych
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded', // Ustawienie typu zawartości
                            },
                            // Dane do wysłania w formacie URL-encoded
                            body: `id_produktu=<?= $product_id ?>&ocena=${wybranaOcena}&imie=${encodeURIComponent(imie)}&opinia=${encodeURIComponent(opinia)}`
                        })
                            .then(response => response.text()) // Odczytanie odpowiedzi jako tekstu
                            .then(data => {
                                console.log(data); // Wyświetlenie odpowiedzi serwera w konsoli (debugowanie)
                                // Po pomyślnym dodaniu opinii,
                                // odświeżenie strony, aby wyświetlić nową opinię
                                location.reload();
                            })
                            .catch(error => console.error('Error:', error)); // Obsługa błędów podczas wysyłania danych
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