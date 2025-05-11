<?php
session_start(); // Uruchomienie sesji
$zalogowany = isset($_SESSION['username']); // Sprawdzenie, czy użytkownik jest zalogowany
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Opinie</title>
  <link rel="stylesheet" href="css/style.css" />
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
    <p>&copy; 2025 Sklep z Butami. Wszystkie prawa zastrzeżone.</p>
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


