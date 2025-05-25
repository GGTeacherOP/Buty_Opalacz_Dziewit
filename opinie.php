<?php
session_start(); // Uruchomienie mechanizmu sesji, umożliwiającego przechowywanie danych o użytkowniku między żądaniami.
include 'auth_utils.php'; // Dołączenie pliku z funkcjami pomocniczymi do autoryzacji (np. sprawdzania ról użytkownika).
$zalogowany = isset($_SESSION['username']); // Sprawdzenie, czy w sesji istnieje zmienna 'username', co oznacza, że użytkownik jest zalogowany.
$rola = $_SESSION['rola'] ?? 'gość';  // Pobranie roli użytkownika z sesji. Jeśli zmienna 'rola' nie istnieje (np. dla niezalogowanych), przypisana zostaje domyślna wartość 'gość'.
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Opinie</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Style CSS dla sekcji wyświetlania opinii */
        #lista-opinii {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .opinia {
            padding: 15px 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-left: 4px solid #007bff; /* Wyraźny pasek z lewej strony opinii */
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05); /* Delikatny cień */

        }

        .opinia h3 {
            margin-top: 0;
            font-size: 1.1em;
            color: #333; /* Ciemnoszary kolor imienia */
        }

        .opinia p {
            margin: 8px 0 0;
            color: #555; /* Szary kolor treści opinii */
        }

        /* Style CSS dla formularza dodawania opinii */
        .formularz-opinii {
            display: inline-block;
            width: 500px;
            padding: 20px;
            background-color: #eef1f5; /* Jasnoszare tło formularza */
            border-radius: 12px;
            margin-top: 20px; /* Odstęp od listy opinii */
        }

        .formularz-opinii h2 {
            margin-top: 0;
            font-size: 1.4em;
            color: #333; /* Ciemnoszary kolor tytułu formularza */
        }

        .formularz-opinii form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .formularz-opinii input,
        .formularz-opinii textarea {
            padding: 10px;
            border: 1px solid #ccc; /* Szara ramka pól formularza */
            border-radius: 6px;
            font-size: 1em;
            width: 100%;
        }

        .formularz-opinii button {
            display: inline-block;
            margin: 0 auto; /* Wyśrodkowanie przycisku */
            width: fit-content; /* Szerokość dopasowana do zawartości */
            padding: 10px 20px;
            background-color: #007bff; /* Niebieskie tło przycisku */
            color: white; /* Biały tekst przycisku */
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s ease; /* Płynna zmiana koloru tła */
        }

        .formularz-opinii button:hover {
            background-color: #0056b3; /* Ciemniejszy niebieski kolor tła po najechaniu */
        }

        /* Style CSS dla przycisku usuwania opinii (ukryty dla zwykłych użytkowników) */
        .usun-opinie {
            margin-top: 10px;
            background-color: #dc3545; /* Czerwone tło przycisku usuwania */
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
        }

        .usun-opinie:hover {
            background-color: #c82333; /* Ciemniejszy czerwony kolor tła po najechaniu */
        }
        /* Style CSS dla stopki strony */
        footer {
            background-color: #191970; /* Ciemnoniebieskie tło stopki */
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: 3rem; /* Odstęp od głównej treści */
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
            <a href="opinie.php" class="active">Opinie</a>
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

        <main class="container opinie">
            <h1>Opinie naszych klientów</h1>

            <section class="lista-opinii" id="lista-opinii">
                <article class="opinia">
                    <h3>Anna K.</h3>
                    <p>Super obsługa i szybka dostawa. Buty są oryginalne i dobrze zapakowane. Polecam!</p>
                    <button class="usun-opinie" hidden>Usuń</button>
                </article>

                <article class="opinia">
                    <h3>Mateusz W.</h3>
                    <p>Kupiłem Jordany – przyszły w 2 dni. Wszystko zgodne z opisem.</p>
                    <button class="usun-opinie" hidden>Usuń</button>
                </article>

                <article class="opinia">
                    <h3>Karolina M.</h3>
                    <p>Duży wybór modeli i dobre ceny. Na pewno wrócę po więcej!</p>
                    <button class="usun-opinie" hidden>Usuń</button>
                </article>

            </section>

            <section class="formularz-opinii">
                <h2>Dodaj swoją opinię</h2>
                <form id="formularz-opinii">
                    <input type="text" id="imie" placeholder="Twoje imię" required />
                    <textarea id="tresc" placeholder="Twoja opinia..." required></textarea>
                    <button type="submit">Wyślij</button>
                </form>
            </section>
        </main>
    </div>
<script>
// Obsługa asynchronicznego wysyłania formularza opinii za pomocą Fetch API
document.getElementById('formularz-opinii').addEventListener('submit', async function(e) {
    e.preventDefault(); // Zapobiega domyślnej akcji formularza, czyli przeładowaniu strony.

    const imie = document.getElementById('imie').value; // Pobranie wartości z pola imienia.
    const tresc = document.getElementById('tresc').value; // Pobranie wartości z pola treści opinii.

    // Wykonanie żądania POST do serwera (do tego samego pliku 'opinie.php') w celu dodania opinii.
    const res = await fetch('opinie.php', {
        method: 'POST', // Metoda HTTP POST, używana do wysyłania danych do serwera.
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}, // Ustawienie nagłówka informującego serwer o formacie danych.
        body: `imie=${encodeURIComponent(imie)}&tresc=${encodeURIComponent(tresc)}` // Dane formularza zakodowane w formacie URL.
    });

    // Sprawdzenie, czy odpowiedź z serwera jest pomyślna (status HTTP 2xx).
    if (res.ok) {
        document.getElementById('imie').value = ''; // Wyczyszczenie pola imienia po pomyślnym wysłaniu.
        document.getElementById('tresc').value = ''; // Wyczyszczenie pola treści opinii.
        wczytajOpinie(); // Wywołanie funkcji wczytującej aktualną listę opinii z serwera.
    }
});

// Asynchroniczna funkcja do pobierania i wyświetlania opinii z serwera.
async function wczytajOpinie() {
    const res = await fetch('opinie.php'); // Wykonanie żądania GET do serwera (do tego samego pliku) w celu pobrania opinii.
    const data = await res.json(); // Parsowanie odpowiedzi z serwera jako JSON.

    const container = document.getElementById('lista-opinii'); // Pobranie elementu kontenera, w którym będą wyświetlane opinie.
    container.innerHTML = ''; // Wyczyszczenie dotychczasowej zawartości kontenera opinii.

    // Iteracja po każdej opinii pobranej z serwera.
    data.forEach(opinia => {
        const div = document.createElement('div'); // Utworzenie nowego elementu div dla każdej opinii.
        div.classList.add('opinia'); // Dodanie klasy CSS 'opinia' do nowego elementu.
        div.innerHTML = `<strong>${opinia.imie}</strong>: ${opinia.komentarz} <em>(${opinia.data_opinii})</em>`; // Wstawienie danych opinii (imię, treść, data) do HTML elementu.
        container.appendChild(div); // Dodanie utworzonego elementu opinii do kontenera na stronie.
    });
}
// Wywołanie funkcji wczytującej opinie przy pierwszym załadowaniu strony, aby wyświetlić istniejące opinie.
wczytajOpinie();
</script>

<?php
$host = "localhost"; // Adres serwera bazy danych.
$db = "buty"; // Nazwa bazy danych.
$user = "root"; // Nazwa użytkownika bazy danych.
$pass = ""; // Hasło użytkownika bazy danych.
$conn = new mysqli($host, $user, $pass, $db); // Utworzenie nowego połączenia z bazą danych MySQL.
$conn->set_charset("utf8"); // Ustawienie domyślnego zestawu znaków dla połączenia na UTF-8.

// Obsługa żądania HTTP POST (dodawanie nowej opinii).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imie = $_POST['imie']; // Pobranie wartości pola 'imie' z danych POST.
    $tresc = $_POST['tresc']; // Pobranie wartości pola 'tresc' z danych POST.
    $id_produktu = NULL; // ID produktu, którego dotyczy opinia. Tutaj ustawione na NULL, co może wymagać zmiany w przyszłości, jeśli opinie mają być powiązane z konkretnymi produktami.
    $ocena = NULL; // Ocena opinii. Tutaj ustawione na NULL, brak implementacji zbierania oceny w formularzu.

    // Przygotowanie zapytania SQL do wstawienia nowej opinii do tabeli 'opinie'.
    $stmt = $conn->prepare("INSERT INTO opinie (id_produktu, imie, ocena, komentarz, data_opinii) VALUES (?, ?, ?, ?, NOW())");
    // Powiązanie typów i wartości parametrów z przygotowanym zapytaniem. 's' oznacza string (tekst), 'i' integer (liczba).
    $stmt->bind_param("isis", $id_produktu, $imie, $ocena, $tresc);
    // Wykonanie przygotowanego zapytania SQL.
    $stmt->execute();
    echo "OK"; // Wysłanie prostej odpowiedzi "OK" do klienta (JavaScript), informującej o pomyślnym dodaniu opinii.
    exit; // Zakończenie wykonywania skryptu PHP po obsłużeniu żądania POST.
}

// Obsługa żądania HTTP GET (pobieranie istniejących opinii).
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Zapytanie SQL pobierające imię, komentarz i datę dodania opinii z tabeli 'opinie', gdzie 'id_produktu' jest NULL (prawdopodobnie opinie ogólne).
    $result = $conn->query("SELECT imie, komentarz, data_opinii FROM opinie WHERE id_produktu IS NULL");
    $opinie = []; // Inicjalizacja pustej tablicy, która będzie przechowywać pobrane opinie.
    // Iteracja po wynikach zapytania SQL.
    while ($row = $result->fetch_assoc()) {
        $opinie[] = $row; // Dodanie każdego wiersza (opinii) do tablicy $opinie jako asocjacyjna tablica.
    }
    // Zakodowanie tablicy z opiniami do formatu JSON i wysłanie jej do klienta (JavaScript).
    echo json_encode($opinie);
}
?>
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
    // Po załadowaniu strony — wczytaj opinie z localStorage
    document.addEventListener("DOMContentLoaded", function () {
      const zapisane = JSON.parse(localStorage.getItem("opinie")) || [];
      const kontener = document.getElementById("lista-opinii");

      // Dodaj dynamiczne opinie
      zapisane.forEach((opinia, index) => {
        const el = stworzOpiniaElement(opinia.imie, opinia.tresc, index);
        kontener.appendChild(el);
      });

      // Dodaj działanie przyciskom "Usuń" dla opinii statycznych
      document.querySelectorAll(".opinia .usun-opinie").forEach(button => {
        button.addEventListener("click", function () {
          this.parentElement.remove();
        });
      });
    });

    // Obsługa formularza dodawania opinii
    document.getElementById("formularz-opinii").addEventListener("submit", function (e) {
      e.preventDefault();

      const imie = document.getElementById("imie").value.trim();
      const tresc = document.getElementById("tresc").value.trim();

      if (imie && tresc) {
        const nowaOpinia = { imie, tresc };
        const stare = JSON.parse(localStorage.getItem("opinie")) || [];
        stare.push(nowaOpinia);
        localStorage.setItem("opinie", JSON.stringify(stare));

        const el = stworzOpiniaElement(imie, tresc, stare.length - 1);
        document.getElementById("lista-opinii").appendChild(el);
        this.reset();
      }
    });

    // Funkcja tworzy element opinii z przyciskiem usuń
    function stworzOpiniaElement(imie, tresc, index) {
      const el = document.createElement("article");
      el.classList.add("opinia");

      el.innerHTML = `
        <h3>${imie}</h3>
        <p>${tresc}</p>
        <button class="usun-opinie" hidden>Usuń</button>
      `;

      // Obsługa kliknięcia przycisku usuń
      el.querySelector(".usun-opinie").addEventListener("click", function () {
        el.remove();

        // Usunięcie z localStorage
        const opinie = JSON.parse(localStorage.getItem("opinie")) || [];
        opinie.splice(index, 1); // usuń z listy po indeksie
        localStorage.setItem("opinie", JSON.stringify(opinie));
      });

      return el;
    }
  </script>
</body>
</html>


