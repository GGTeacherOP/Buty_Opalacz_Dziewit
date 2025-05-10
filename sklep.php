<?php
session_start(); // Uruchomienie sesji
$zalogowany = isset($_SESSION['username']); // Sprawdzenie, czy użytkownik jest zalogowany
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sklep z butami</title>
  <link rel="stylesheet" href="css/style.css" />
  
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

    <nav>
      <div class="prz">Kategorie</div>
      <div class="zbior">
        <a href="#">Nike</a>
        <a href="#">Adidas</a>
        <a href="#">Jordan</a>
        <a href="#">Yeezy</a>
        <a href="#">Nowości</a>
        <a href="#">Wyprzedaż</a>
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
        <option value="Sneakersy">Sneakersy</option>
        <option value="Trampki">Trampki</option>
        <option value="Klapki">Klapki</option>
        <option value="Treningowe">Buty Treningowe</option>
        <option value="Biegania">Buty do Biegania</option>
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

    <main class="bestsellery produkty-filtr">
      <div class="produkty" id="productList">
        <!-- Produkty -->
        <a href="A1ForceBiale.php" class="produkt" data-brand="Nike" data-price="499" data-type="Sneakersy">
          <img src="img/Nike/AF1/AF1white.jpg" alt="Air Force 1 Białe" />
          <p>Nike Air Force 1 Białe</p>
          <span>499 zł</span>
        </a>

        <a href="J1Mocha.php" class="produkt" data-brand="Jordan" data-price="1249" data-type="Sneakersy">
          <img src="img/Jordan/Mocha/Mocha1.jpeg" alt="Jordan 1 Mocha" />
          <p>Jordan 1 Mocha</p>
          <span>1249 zł</span>
        </a>

        
        <a href="Campusy.php" class="produkt" data-brand="Adidas" data-price="529" data-type="Sneakersy">
          <img src="img/Adidas/Campus/1.avif" alt="Adidas Campus 00s" />
          <p>Adidas Campus 00s Beżowe</p>
          <span>529 zł</span>
        </a>


         <a href="Campusy2.php" class="produkt" data-brand="Adidas" data-price="529" data-type="Sneakersy">
          <img src="img/Adidas/Campus/campus1.jpg" alt="Adidas Campus 00s" />
          <p>Adidas Campus 00s Czarne</p>
          <span>529 zł</span>
        </a>

        <a href="Samba.php" class="produkt" data-brand="Adidas" data-price="429" data-type="Sneakersy">
          <img src="img/Adidas/Samba/samba1.jpg" alt="Adidas Samba OG White" />
          <p>Adidas Samba OG </p>
          <span>429 zł</span>
        </a>

        <a href="JordanMilitary.php" class="produkt" data-brand="Jordan" data-price="1399" data-type="Sneakersy">
          <img src="img/Jordan/Military/Military1.jpg" alt="Jordan 4 Military Black" />
          <p>Jordan 4 Military Black</p>
          <span>1399 zł</span>
        </a>
        <a href="NikePegasusPrem.php" class="produkt" data-brand="Nike" data-price="899" data-type="Biegania">
          <img src="img/Nike/Nike Pegasus Premium/pegasusprem1.png" alt="Nike Pegasus Premium" />
          <p>Nike Pegasus Premium</p>
          <span>899 zł</span>
        </a>

        <a href="AirMax.php" class="produkt" data-brand="Nike" data-price="649" data-type="Sneakersy">
          <img src="img/Nike/AIR MAX/MAX1.png" alt="Nike Air Max 90" />
          <p>Nike Air Max 90</p>
          <span>649 zł</span>
        </a>
        <a href="RebookFiori.php" class="produkt" data-brand="Reebok" data-price="250" data-type="Biegania">
          <img src="img/Reebook/Reebok FIORI/Rebook FIORI1.jpg" alt="Reebook FIORI "/>
          <p>Reebook FIORI</p>
          <span>250 zł</span>
        </a>

        <a href="ConvAllStar.php" class="produkt" data-brand="Converse" data-price="349" data-type="Trampki">
          <img src="img/Converse/ConverseAllStar/ConverseALLStar1.jpg "alt="Converse All Star">
          <p>Converse Chuck Taylor All Star</p>
          <span>349 zł</span>
        </a>
        
        <a href="ConvPlatform.php" class="produkt" data-brand="Converse" data-price="399" data-type="Trampki">
          <img src="img/Converse/ConversePlatform/ConversePlatform1 (1).jpg" alt="Converse All Star Platform">
          <p>Converse All Star Platform Czarny</p>
          <span>399 zł</span>
        </a>

        <a href="AdidasFor.php" class="produkt" data-brand="Adidas" data-price="499" data-type="Sneakersy">
          <img src="img/Adidas/ForumBlack/Forum1.jpg" alt="Adidas Forum Low" />
          <p>Adidas Forum Low</p>
          <span>499 zł</span>
        </a>

        <a href="Jordan1Chi.php" class="produkt" data-brand="Jordan" data-price="1599" data-type="Sneakersy">
          <img src="img/Jordan/J1Chicago/J1Chicago1.jpg" alt="Jordan 1 Chicago" />
          <p>Jordan 1 Chicago</p>
          <span>1599 zł</span>
        </a>
        <a href="CopaPure.php" class="produkt" data-brand="Adidas" data-price="350" data-type="Treningowe">
          <img src="img/Adidas/COPA PURE 2 CLUB IN/add1.jpg" alt="COPA PURE" />
          <p>Adidas COPA PURE 2</p>
          <span>350 zł</span>
        </a>

        <a href="VansOld.php" class="produkt" data-brand="Vans" data-price="379" data-type="Trampki">
          <img src="img/VANS/VansOld/VansOld1.avif" alt="Vans Old Skool">
          <p>Vans Old Skool Czarny</p>
          <span>379 zł</span>
        </a>
        
        <a href="VansOld.php" class="produkt" data-brand="Vans" data-price="429" data-type="Trampki">
          <img src="img/VANS/VansSk8/VansSk81.avif" alt="Vans Sk8-Hi">
          <p>Vans Sk8-Hi Biało-Czarne</p>
          <span>429 zł</span>
        </a>


        <a href="klapkiNikeB.php" class="produkt" data-brand="Nike" data-price="199" data-type="Klapki">
          <img src="img/Nike/KlapkiBiale/1.avif" alt="Nike Klapki Białe" />
          <p>Nike Klapki Białe</p>
          <span>199 zł</span>
        </a>
        <a href="klapkiNikeC.php" class="produkt" data-brand="Nike" data-price="199" data-type="Klapki">
          <img src="img/Nike/KlpakiCzarne/1.avif" alt="Nike Klapki Czarne" />
          <p>Nike Klapki Czarne</p>
          <span>199 zł</span>
        </a>
        

        <a href="UnderArmourInf.php" class="produkt" data-brand="Under" data-price="699" data-type="biegania">
          <img src="img/UnderArmour/Infinite/UA_W_Infinite_Elite_2_1.png" alt="Under Armour Infinite" />
          <p>Under Armour Infinite</p>
          <span>699</span>
        </a>
        
        <a href="AdidasKlapkiB.php" class="produkt" data-brand="Adidas" data-price="179" data-type="Klapki">
          <img src="img/Adidas/KlapkiBiale/1.avif" alt="Adidas Klapki Białe" />
          <p>Adidas Klapki Białe</p>
          <span>179 zł</span>
        </a>
        <a href="AdidasKlapkiC.php" class="produkt" data-brand="Adidas" data-price="179" data-type="Klapki">
          <img src="img/Adidas/KlapkiCzarne/1.avif" alt="Adidas Klapki Czarne" />
          <p>Adidas Klapki Czarne</p>
          <span>179 zł</span>
        </a>

        <a href="JordanKlapkiC.php" class="produkt" data-brand="Jordan" data-price="250" data-type="Klapki">
          <img src="img/Jordan/KlapkiCzarne/1.avif" alt="Jordan Klapki Czarne" />
          <p>Jordan Klapki Czarne</p>
          <span>250 zł</span>
        </a>

        <a href="UAMagnetico.php" class="produkt" data-brand="Under" data-price="299" data-type="Treningowe">
          <img src="img/UnderArmour/Magnetico/UA_Magnetico_Elite_4Fg_1.png" alt="Under Armour Magnetico" />
          <p>Under Armour Magnetico</p>
          <span>299</span>
        </a>
        <a href="JordanKlapkiB.php" class="produkt" data-brand="Jordan" data-price="250" data-type="Klapki">
          <img src="img/Jordan/KlapkiBiale/1.avif" alt="Jordan Klapki Białe" />
          <p>Jordan Klapki Białe</p>
          <span>250 zł</span>
        </a>


        
       
      </div>
    </main>

    <footer class="footer">
      <p>&copy; 2025 Sklep z Butami. Wszystkie prawa zastrzeżone.</p>
    </footer>
  </div>
  <script>
    // Pobieranie elementów filtrów
    const poleWyszukiwania = document.getElementById("searchInput");
    const filtrMarka = document.getElementById("filterBrand");
    const filtrRodzaj = document.getElementById("filterType");
    const suwakCeny = document.getElementById("priceRange");
    const wartoscCeny = document.getElementById("priceValue");
    const sortowanieCeny = document.getElementById("sortOrder");
  
    const listaProduktow = document.getElementById("productList");
    const wszystkieProdukty = Array.from(listaProduktow.querySelectorAll(".produkt"));
  
    // Aktualizacja tekstu przy suwaku
    suwakCeny.addEventListener("input", () => {
      wartoscCeny.textContent = suwakCeny.value + " zł";
      filtrujProdukty();
    });
  
    // Nasłuchiwanie zmian na filtrach
    [poleWyszukiwania, filtrMarka, filtrRodzaj, sortowanieCeny].forEach(element => {
      element.addEventListener("input", filtrujProdukty);
    });
  
    // Główna funkcja filtrująca
    function filtrujProdukty() {
      const tekstWyszukiwania = poleWyszukiwania.value.toLowerCase();
      const wybranaMarka = filtrMarka.value;
      const wybranyRodzaj = filtrRodzaj.value;
      const maksCena = parseInt(suwakCeny.value);
      const kolejnoscSortowania = sortowanieCeny.value;
  
      // Filtrowanie produktów
      let pasujaceProdukty = wszystkieProdukty.filter(produkt => {
        const nazwa = produkt.querySelector("p").textContent.toLowerCase();
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
    }
  
    // Pierwsze wywołanie na starcie
    filtrujProdukty();
  </script>
  
</body>
</html>





