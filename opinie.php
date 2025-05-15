<?php
session_start(); // Uruchomienie sesji
include 'auth_utils.php';
$zalogowany = isset($_SESSION['username']);
$rola = $_SESSION['rola'] ?? 'gość';  // Domyślnie 'gość' dla niezalogowanych
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
    .lista-opinii {
      display: flex;
      flex-direction: column;
      gap: 20px;
      padding: 20px;
      background-color: #f9f9f9;
      border-radius: 12px;
      margin-bottom: 40px;
    }

    .opinia {
      padding: 15px 20px;
      background-color: #fff;
      border: 1px solid #ddd;
      border-left: 4px solid #007bff;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      position: relative;
    }

    .opinia h3 {
      margin-top: 0;
      font-size: 1.1em;
      color: #333;
    }

    .opinia p {
      margin: 8px 0 0;
      color: #555;
    }

    .formularz-opinii {
      padding: 20px;
      background-color: #eef1f5;
      border-radius: 12px;
    }

    .formularz-opinii h2 {
      margin-top: 0;
      font-size: 1.4em;
    }

    .formularz-opinii form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .formularz-opinii input,
    .formularz-opinii textarea {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1em;
      width: 100%;
    }

    .formularz-opinii button {
      width: fit-content;
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .formularz-opinii button:hover {
      background-color: #0056b3;
    }

    .usun-opinie {
      margin-top: 10px;
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 0.9em;
    }

    .usun-opinie:hover {
      background-color: #c82333;
    }
    footer {
  background-color: #191970;
  color: white;
  text-align: center;
  padding: 1rem;
  margin-top: 3rem;
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

    <main class="container opinie">
      <h1>Opinie naszych klientów</h1>

      <section class="lista-opinii" id="lista-opinii">
        <!-- Statyczne opinie -->
        <article class="opinia">
          <h3>Anna K.</h3>
          <p>Super obsługa i szybka dostawa. Buty są oryginalne i dobrze zapakowane. Polecam!</p>
          <button class="usun-opinie">Usuń</button>
        </article>

        <article class="opinia">
          <h3>Mateusz W.</h3>
          <p>Kupiłem Jordany – przyszły w 2 dni. Wszystko zgodne z opisem.</p>
          <button class="usun-opinie">Usuń</button>
        </article>

        <article class="opinia">
          <h3>Karolina M.</h3>
          <p>Duży wybór modeli i dobre ceny. Na pewno wrócę po więcej!</p>
          <button class="usun-opinie">Usuń</button>
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
        <button class="usun-opinie">Usuń</button>
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


