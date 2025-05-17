<?php
session_start();
include 'auth_utils.php';
$zalogowany = isset($_SESSION['username']);
$rola = $_SESSION['rola'] ?? 'gość';

// Pobierz parametr type z URL
$selectedType = isset($_GET['type']) ? $_GET['type'] : '';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sklep z butami</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" href="img/favi2.png" type="image/png">
  <style>
    .zbior a.active-category {
      color: #007bff;
      font-weight: bold;
      text-decoration: underline;
    }
    .prz {
      margin-bottom: 10px;
      font-weight: bold;
    }
    .type-filter {
      margin-top: 15px;
    }
    .filtry-container {
      margin-top: 20px;
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
    <div class="prz">Marki</div>
    <div class="zbior brand-filter"> <!-- Dodaj klasę brand-filter -->
        <a href="#" data-brand="">Wszystkie</a> 
        <a href="#" data-brand="Nike">Nike</a>
        <a href="#" data-brand="Adidas">Adidas</a>
        <a href="#" data-brand="Jordan">Jordan</a>
        <a href="#" data-brand="Vans">Vans</a>
        <a href="#" data-brand="Under">Under Armour</a>
        <a href="#" data-brand="Converse">Converse</a>
    </div>
    
    <div class="prz" style="margin-top: 20px;"></div>
    <div class="zbior type-filter">
        
        <a href="#" data-type="Sneakersy">Sneakersy</a>
        <a href="#" data-type="Trampki">Trampki</a>
        <a href="#" data-type="Klapki">Klapki</a>
        <a href="#" data-type="Treningowe">Treningowe</a>
        <a href="#" data-type="Biegania">Do biegania</a>
    </div>
</nav>

    <!-- Filtry -->
    <div class="filtry-container">
      <input type="text" id="searchInput" placeholder="Szukaj butów..." />

      <select id="filterBrand">
        <option value="">Wszystkie marki</option>
        <option value="Nike">Nike</option>
        <option value="Adidas">Adidas</option>
        <option value="Jordan">Jordan</option>
        <option value="Vans">Vans</option>
        <option value="Converse">Converse</option>
        <option value="Reebok">Reebok</option>
        <option value="Under">Under Armour</option>
      </select>

     <select id="filterType">
    <option value="">Wszystkie rodzaje</option>
    <option value="Sneakersy" <?= $selectedType === 'Sneakersy' ? 'selected' : '' ?>>Sneakersy</option>
    <option value="Trampki" <?= $selectedType === 'Trampki' ? 'selected' : '' ?>>Trampki</option>
    <option value="Klapki" <?= $selectedType === 'Klapki' ? 'selected' : '' ?>>Klapki</option>
    <option value="Treningowe" <?= $selectedType === 'Treningowe' ? 'selected' : '' ?>>Buty Treningowe</option>
    <option value="Biegania" <?= $selectedType === 'Biegania' ? 'selected' : '' ?>>Buty do Biegania</option>
</select>

      <label>
        Cena do:
        <input type="range" id="priceRange" min="0" max="2000" step="50" value="2000" />
        <span id="priceValue">2000 zł</span>
      </label>

      <select id="sortOrder">
        <option value="">Sortuj według ceny</option>
        <option value="asc">Cena rosnąco</option>
        <option value="desc">Cena malejąco</option>
      </select>
    </div>

   <section class="bestsellery">
    <h2>Nasze Produkty</h2>
    <div class="produkty-grid" id="productList">
      
        <div class="produkt-card" data-brand="Nike" data-price="499" data-type="Sneakersy">
            <img src="img/Nike/AF1/AF1white.jpg" alt="Air Force 1 Białe" />
            <h3>Nike Air Force 1 Białe</h3>
            <p class="cena">499 zł</p>
            <a href="A1ForceBiale.php" class="btn-zobacz">Zobacz</a>
        </div>

        <div class="produkt-card" data-brand="Jordan" data-price="1249" data-type="Sneakersy">
            <img src="img/Jordan/Mocha/Mocha1.jpeg" alt="Jordan 1 Mocha" />
            <h3>Jordan 1 Mocha</h3>
            <p class="cena">1249 zł</p>
            <a href="J1Mocha.php" class="btn-zobacz">Zobacz</a>
        </div>

        <div class="produkt-card" data-brand="Adidas" data-price="529" data-type="Sneakersy">
            <img src="img/Adidas/Campus/1.avif" alt="Adidas Campus 00s" />
            <h3>Adidas Campus 00s Beżowe</h3>
            <p class="cena">529 zł</p>
            <a href="Campusy.php" class="btn-zobacz">Zobacz</a>
        </div>

        <div class="produkt-card" data-brand="Adidas" data-price="529" data-type="Sneakersy">
            <img src="img/Adidas/Campus/campus1.jpg" alt="Adidas Campus 00s Czarne" />
            <h3>Adidas Campus 00s Czarne</h3>
            <p class="cena">529 zł</p>
            <a href="Campusy2.php" class="btn-zobacz">Zobacz</a>
        </div>

        <div class="produkt-card" data-brand="Adidas" data-price="429" data-type="Sneakersy">
            <img src="img/Adidas/Samba/samba1.jpg" alt="Adidas Samba OG White" />
            <h3>Adidas Samba OG</h3>
            <p class="cena">429 zł</p>
            <a href="Samba.php" class="btn-zobacz">Zobacz</a>
        </div>

        <div class="produkt-card" data-brand="Jordan" data-price="1399" data-type="Sneakersy">
            <img src="img/Jordan/Military/Military1.jpg" alt="Jordan 4 Military Black" />
            <h3>Jordan 4 Military Black</h3>
            <p class="cena">1399 zł</p>
            <a href="JordanMilitary.php" class="btn-zobacz">Zobacz</a>
        </div>
        <div class="produkt-card" data-brand="Nike" data-price="899" data-type="Biegania">
            <img src="img/Nike/Nike Pegasus Premium/pegasusprem1.png" alt="Nike Pegasus Premium" />
            <h3>Nike Pegasus Premium</h3>
            <p class="cena">899 zł</p>
            <a href="NikePegasusPrem.php" class="btn-zobacz">Zobacz</a>
        </div>

        <div class="produkt-card" data-brand="Nike" data-price="649" data-type="Sneakersy">
            <img src="img/Nike/AIR MAX/MAX1.png" alt="Nike Air Max 90" />
            <h3>Nike Air Max 90</h3>
            <p class="cena">649 zł</p>
            <a href="AirMax.php" class="btn-zobacz">Zobacz</a>
        </div>
        <div class="produkt-card" data-brand="Reebok" data-price="250" data-type="Biegania">
            <img src="img/Reebook/Reebok FIORI/Rebook FIORI1.jpg" alt="Reebook FIORI "/>
            <h3>Reebook FIORI</h3>
            <p class="cena">250 zł</p>
            <a href="RebookFiori.php" class="btn-zobacz">Zobacz</a>
        </div>

        <div class="produkt-card" data-brand="Converse" data-price="349" data-type="Trampki">
            <img src="img/Converse/ConverseAllStar/ConverseALLStar1.jpg" alt="Converse All Star">
            <h3>Converse Chuck Taylor All Star</h3>
            <p class="cena">349 zł</p>
            <a href="ConvAllStar.php" class="btn-zobacz">Zobacz</a>
        </div>

        <div class="produkt-card" data-brand="Converse" data-price="399" data-type="Trampki">
            <img src="img/Converse/ConversePlatform/ConversePlatform1 (1).jpg" alt="Converse All Star Platform">
            <h3>Converse All Star Platform Czarny</h3>
            <p class="cena">399 zł</p>
            <a href="ConvPlatform.php" class="btn-zobacz">Zobacz</a>
        </div>

        <div class="produkt-card" data-brand="Adidas" data-price="499" data-type="Sneakersy">
            <img src="img/Adidas/ForumBlack/Forum1.jpg" alt="Adidas Forum Low" />
            <h3>Adidas Forum Low</h3>
            <p class="cena">499 zł</p>
            <a href="AdidasFor.php" class="btn-zobacz">Zobacz</a>
        </div>

        <div class="produkt-card" data-brand="Jordan" data-price="1599" data-type="Sneakersy">
            <img src="img/Jordan/J1Chicago/J1Chicago1.jpg" alt="Jordan 1 Chicago" />
            <h3>Jordan 1 Chicago</h3>
            <p class="cena">1599 zł</p>
            <a href="Jordan1Chi.php" class="btn-zobacz">Zobacz</a>
        </div>
        <div class="produkt-card" data-brand="Adidas" data-price="350" data-type="Treningowe">
            <img src="img/Adidas/COPA PURE 2 CLUB IN/add1.jpg" alt="COPA PURE" />
            <h3>Adidas COPA PURE 2</h3>
            <p class="cena">350 zł</p>
            <a href="CopaPure.php" class="btn-zobacz">Zobacz</a>
        </div>

        <div class="produkt-card" data-brand="Vans" data-price="379" data-type="Trampki">
            <img src="img/VANS/VansOld/VansOld1.avif" alt="Vans Old Skool">
            <h3>Vans Old Skool Czarny</h3>
            <p class="cena">379 zł</p>
            <a href="VansOld.php" class="btn-zobacz">Zobacz</a>
        </div>

        <div class="produkt-card" data-brand="Vans" data-price="429" data-type="Trampki">
            <img src="img/VANS/VansSk8/VansSk81.avif" alt="Vans Sk8-Hi">
            <h3>Vans Sk8-Hi Biało-Czarne</h3>
            <p class="cena">429 zł</p>
            <a href="VansOld.php" class="btn-zobacz">Zobacz</a>
        </div>


        <div class="produkt-card" data-brand="Nike" data-price="199" data-type="Klapki">
            <img src="img/Nike/KlapkiBiale/1.avif" alt="Nike Klapki Białe" />
            <h3>Nike Klapki Białe</h3>
            <p class="cena">199 zł</p>
            <a href="klapkiNikeB.php" class="btn-zobacz">Zobacz</a>
        </div>
        <div class="produkt-card" data-brand="Nike" data-price="199" data-type="Klapki">
            <img src="img/Nike/KlpakiCzarne/1.avif" alt="Nike Klapki Czarne" />
            <h3>Nike Klapki Czarne</h3>
            <p class="cena">199 zł</p>
            <a href="klapkiNikeC.php" class="btn-zobacz">Zobacz</a>
        </div>


        <div class="produkt-card" data-brand="Under" data-price="699" data-type="biegania">
            <img src="img/UnderArmour/Infinite/UA_W_Infinite_Elite_2_1.png" alt="Under Armour Infinite" />
            <h3>Under Armour Infinite</h3>
            <p class="cena">699 zł</p>
            <a href="UnderArmourInf.php" class="btn-zobacz">Zobacz</a>
        </div>

        <div class="produkt-card" data-brand="Adidas" data-price="179" data-type="Klapki">
            <img src="img/Adidas/KlapkiBiale/1.avif" alt="Adidas Klapki Białe" />
            <h3>Adidas Klapki Białe</h3>
            <p class="cena">179 zł</p>
            <a href="AdidasKlapkiB.php" class="btn-zobacz">Zobacz</a>
        </div>
        <div class="produkt-card" data-brand="Adidas" data-price="179" data-type="Klapki">
            <img src="img/Adidas/KlapkiCzarne/1.avif" alt="Adidas Klapki Czarne" />
            <h3>Adidas Klapki Czarne</h3>
            <p class="cena">179 zł</p>
            <a href="AdidasKlapkiC.php" class="btn-zobacz">Zobacz</a>
        </div>

        <div class="produkt-card" data-brand="Jordan" data-price="250" data-type="Klapki">
            <img src="img/Jordan/KlapkiCzarne/1.avif" alt="Jordan Klapki Czarne" />
            <h3>Jordan Klapki Czarne</h3>
            <p class="cena">250 zł</p>
            <a href="JordanKlapkiC.php" class="btn-zobacz">Zobacz</a>
        </div>

        <div class="produkt-card" data-brand="Under" data-price="299" data-type="Treningowe">
            <img src="img/UnderArmour/Magnetico/UA_Magnetico_Elite_4Fg_1.png" alt="Under Armour Magnetico" />
            <h3>Under Armour Magnetico</h3>
            <p class="cena">299 zł</p>
            <a href="UAMagnetico.php" class="btn-zobacz">Zobacz</a>
        </div>
        <div class="produkt-card" data-brand="Jordan" data-price="250" data-type="Klapki">
            <img src="img/Jordan/KlapkiBiale/1.avif" alt="Jordan Klapki Białe" />
            <h3>Jordan Klapki Białe</h3>
            <p class="cena">250 zł</p>
            <a href="JordanKlapkiB.php" class="btn-zobacz">Zobacz</a>
        </div>

    </div>
</section>
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

   <script>
    // Pobieranie elementów
    const poleWyszukiwania = document.getElementById("searchInput");
    const filtrMarka = document.getElementById("filterBrand");
    const filtrRodzaj = document.getElementById("filterType");
    const suwakCeny = document.getElementById("priceRange");
    const wartoscCeny = document.getElementById("priceValue");
    const sortowanieCeny = document.getElementById("sortOrder");
    const listaProduktow = document.getElementById("productList");
    const wszystkieProdukty = Array.from(listaProduktow.querySelectorAll(".produkt-card"));
    const brandLinks = document.querySelectorAll('.brand-filter a');
    const typeLinks = document.querySelectorAll('.type-filter a');

    // Aktualizacja tekstu przy suwaku
    suwakCeny.addEventListener("input", () => {
      wartoscCeny.textContent = suwakCeny.value + " zł";
      filtrujProdukty();
    });

    // Nasłuchiwanie zmian na filtrach
    [poleWyszukiwania, filtrMarka, filtrRodzaj, sortowanieCeny].forEach(element => {
      element.addEventListener("change", filtrujProdukty);
    });

    // Obsługa kliknięcia w markę
    brandLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const brand = link.dataset.brand;
        filtrMarka.value = brand;
        filtrRodzaj.value = '';
        resetujPozostaleFiltry();
        filtrujProdukty();
        aktualizujAktywneLinki();
      });
    });

    // Obsługa kliknięcia w rodzaj buta
    typeLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const type = link.dataset.type;
        filtrRodzaj.value = type;
        filtrMarka.value = '';
        resetujPozostaleFiltry();
        filtrujProdukty();
        aktualizujAktywneLinki();
      });
    });

    // Funkcja resetująca pozostałe filtry
    function resetujPozostaleFiltry() {
      poleWyszukiwania.value = '';
      suwakCeny.value = '2000';
      wartoscCeny.textContent = '2000 zł';
      sortowanieCeny.value = '';
    }

    // Funkcja aktualizująca aktywne linki
    function aktualizujAktywneLinki() {
      // Resetuj wszystkie linki
      brandLinks.forEach(link => link.classList.remove('active-category'));
      typeLinks.forEach(link => link.classList.remove('active-category'));

      // Ustaw aktywne linki na podstawie filtrów
      const wybranaMarka = filtrMarka.value;
      const wybranyRodzaj = filtrRodzaj.value;

      // Aktywuj link marki
      if (wybranaMarka) {
        document.querySelector(`.brand-filter a[data-brand="${wybranaMarka}"]`).classList.add('active-category');
      } else {
        document.querySelector('.brand-filter a[data-brand=""]').classList.add('active-category');
      }

      // Aktywuj link rodzaju
      if (wybranyRodzaj) {
        document.querySelector(`.type-filter a[data-type="${wybranyRodzaj}"]`).classList.add('active-category');
      } else {
        document.querySelector('.type-filter a[data-type=""]').classList.add('active-category');
      }
    }

    // Główna funkcja filtrująca
    function filtrujProdukty() {
      const tekstWyszukiwania = poleWyszukiwania.value.toLowerCase();
      const wybranaMarka = filtrMarka.value;
      const wybranyRodzaj = filtrRodzaj.value;
      const maksCena = parseInt(suwakCeny.value);
      const kolejnoscSortowania = sortowanieCeny.value;

      // Filtrowanie produktów
      let pasujaceProdukty = wszystkieProdukty.filter(produkt => {
        const nazwa = produkt.querySelector("h3").textContent.toLowerCase();
        const marka = produkt.dataset.brand;
        const rodzaj = produkt.dataset.type;
        const cena = parseInt(produkt.dataset.price);

        return (
          nazwa.includes(tekstWyszukiwania) &&
          (!wybranaMarka || marka === wybranaMarka) &&
          (!wybranyRodzaj || rodzaj === wybranyRodzaj) &&
          cena <= maksCena
        );
      });

      // Sortowanie cen
      if (kolejnoscSortowania === "asc") {
        pasujaceProdukty.sort((a, b) => a.dataset.price - b.dataset.price);
      } else if (kolejnoscSortowania === "desc") {
        pasujaceProdukty.sort((a, b) => b.dataset.price - a.dataset.price);
      }

      // Aktualizacja widoku
      listaProduktow.innerHTML = "";
      pasujaceProdukty.forEach(produkt => listaProduktow.appendChild(produkt));
      
      // Aktualizacja aktywnych linków
      aktualizujAktywneLinki();
    }

    // Inicjalizacja - pierwsze wywołanie na starcie
    document.addEventListener('DOMContentLoaded', function() {
      // Sprawdź parametry URL
      const urlParams = new URLSearchParams(window.location.search);
      const selectedType = urlParams.get('type');
      const selectedBrand = urlParams.get('brand');
      
      // Ustaw filtry na podstawie URL
      if (selectedType) filtrRodzaj.value = selectedType;
      if (selectedBrand) filtrMarka.value = selectedBrand;
      
      // Pierwsze filtrowanie
      filtrujProdukty();
      
      // Przewiń do sekcji produktów jeśli są parametry
      if (selectedType || selectedBrand) {
        document.querySelector('.bestsellery').scrollIntoView({
          behavior: 'smooth'
        });
      }
    });
  </script>
</body>
</html>