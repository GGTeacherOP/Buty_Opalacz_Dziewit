<?php
session_start(); // Uruchomienie sesji
$zalogowany = isset($_SESSION['username']); // Sprawdzenie, czy użytkownik jest zalogowany
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
        .calosc{
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        #wstep{
            background-color: rgb(134, 134, 134);
            height: 60px;
            font-size: large;
            margin-bottom: 2rem;
            color:rgb(228, 228, 228);
            padding: 6px;
            text-align: center;
            border-radius: 22px;
        }
        .uwaga{
            background-color: rgb(247, 255, 173);
            font-size: 20px;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;

        }
        .uwaga:hover{
            transform: translateY(-5px);
        }
        .uwaga h2{
            color: rgb(109, 36, 36);
        }
        .info{
            font-size: 20px;
            width:50%;
            float: left;
            
        }
        .kontakt{
            float: right;
        }
        form {
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
 
form input,
form textarea {
  padding: 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  background-color: #fafafa;
}
form button {
  padding: 1rem;
  border: none;
  background-color: #222;
  color: white;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  border-radius: 8px;
}
 
form button:hover {
  background-color: #000;
  font-size:1.3rem;
  padding:0.83rem;
}
    .zbior {
      border-bottom: 1px solid #ddd;
      padding: 10px 0;
    }

    .pytanie {
        font-size: larger;
      font-weight: bold;
      cursor: pointer;
      position: relative;
    }

    .pytanie::after {
      content: "▼";
      position: absolute;
      right: 0;
      transition: transform 0.3s;
    }

    .pytanie.active::after {
      transform: rotate(180deg);
    }

    .odpowiedz {
      display: none;
      padding-top: 10px;
    }

    .odpowiedz.show {
      display: block;
    }
    p{
        font-size: large;
    }
    </style>
</head>
<body>
  <div class="wrapper">
    <header>
        <a href="index.php">Strona Główna</a>
            <a href="sklep.php">Sklep</a>
            <a href="koszyk.php">Koszyk</a>
            <a href="kontakt.html" class="active">Kontakt</a>
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
    <main>
        <div class="calosc">
            <div id="wstep">
                <h2>Cześć! Jak możemy pomóc?</h2>
            </div>
            <div class="uwaga">
                <h2>Uwaga!</h2>
                <p>W związku z dużą liczbą zgłoszeń, czas oczekiwania na rozmowę z konsultantem na infolinii znacznie się wydłużył.
                Jeśli nie możesz czekać, skontaktuj się z nami pod adresem <b>info@buty.pl</b><br><br>
                Prosimy o wskazanie numeru zamówienia oraz tematu sprawy w tytule wiadomości - pomoże nam to szybciej i skuteczniej zająć się Twoim zgłoszeniem.<br><br>
                Dziękujemy za zrozumienie i prosimy o cierpliwość.</p>
            </div><br><br><br>
            <div class="zbior">
    <div class="pytanie">Kiedy dostanę zwrot pieniędzy?</div>
    <div class="odpowiedz">
      <p><strong>Zwrot w sklepie internetowym</strong><br>
      Pieniądze otrzymasz maksymalnie do 14 dni. Zazwyczaj jest to kilka dni roboczych.</p><br>
      
      <p><strong>Zwrot produktów opłaconych metodą PayPo</strong><br>
      Pieniądze otrzymasz przez serwis PayPo. Jeśli zwrot produktów nastąpił w ciągu 30 dni od zakupu, PayPo zaktualizuje Twoje saldo w ciągu 5 dni roboczych.</p><br>
      
      <p><strong>Zwrot produktów opłaconych Kartą Podarunkową</strong><br>
      Pieniądze otrzymasz na nową Kartę Podarunkową, którą wyślemy do Ciebie mailem.</p><br>

      <p><strong>Zwrot w sklepie stacjonarnym</strong><br>
      Zwrot środków nastąpi w formie doładowania Karty Podarunkowej CCCxHalfPrice według zasad z Regulaminu Kart Podarunkowych. W razie awarii terminy mogą się wydłużyć o kolejne 5 dni roboczych.</p>
    </div>
  </div>

  <div class="zbior">
    <div class="pytanie">Jak mogę złożyć reklamację?</div>
    <div class="odpowiedz">
      <p>Aby złożyć reklamację, skontaktuj się z działem obsługi klienta lub odwiedź najbliższy sklep stacjonarny.</p>
    </div>
  </div>

  <div class="zbior">
    <div class="pytanie">Co zrobić jeśli przerwało mi płatność?</div>
    <div class="odpowiedz">
      <p>Sprawdź status transakcji w historii płatności. Jeśli płatność nie została pobrana, możesz złożyć zamówienie ponownie.</p>
    </div>
  </div>

  <div class="zbior">
    <div class="pytanie">Jak mogę zwrócić produkt?</div>
    <div class="odpowiedz">
      <p>Nasze zwroty są szybkie i proste:<br> 
        - Jeśli zwracasz do 3 produktów włącznie, skorzystaj z InPost Paczkomat® 24/7,<br>
        - Jeśli zwracasz 4 lub więcej produktów, zwrócisz je za pośrednictwem Kuriera DPD.<br><br>
      Każdy produkt możesz też zwrócić w najbliższym sklepie stacjonarnym.<br><br>
      Chcesz zwrócić produkt przez internet?<br>
      1. Zaloguj się i znajdź zakładkę zwroty na Twoim koncie. <br> 
      2. Wybierz, które produkty chcesz zwrócić.  <br>
      3. Idź do Paczkomatu® albo czekaj na kuriera.  <br><br>
      Jeśli zwracasz produkty Paczkomatem®, wyślemy do Ciebie SMS z kodem do dowolnego Paczkomatu®. Nie musisz drukować etykiety.<br>
      Jeśli zwracasz Kurierem DPD podaj dokładny adres, z którego chcesz nadać paczkę. Później zmiana adresu nie będzie możliwa.</p>
    </div>
  </div>

  <script>
    document.querySelectorAll('.pytanie').forEach(item => {
      item.addEventListener('click', () => {
        item.classList.toggle('active');
        const answer = item.nextElementSibling;
        answer.classList.toggle('show');
      });
    });
  </script><br><br>
            <section class="kontakt">
            <h1>Czy masz inne pytania? Napisz do nas</h1>
            <form action="" method="post">
            <input type="text" name="imie" placeholder="Twoje imię" required>
            <input type="email" name="email" placeholder="Twój email" required>
            <textarea name="wiad" placeholder="Napisz tutaj swoje pytanie" required></textarea>
            <button type="submit" name="submit" class="submit">Wyślij</button>
            </form>
            </section>
            <div class="info">
                <h2>Informacje kontaktowe</h2>
                <p>Buty.pl - Mielec<br>
                +48 609 350 471<br>
                info@buty.pl</p>
            </div>
        </div>
        <?php
        $con = mysqli_connect('localhost', 'root', '', 'buty');
        $sql1="SELECT `imie`,`email`,`pytanie`,`email` FROM wiadomosci;";
        $que=mysqli_query($con,$sql1);
        if(isset($_POST['submit']) && $_POST['imie'] && isset($_POST['email']) && isset($_POST['wiad'])){
            $imie = $_POST['imie'];
            $email = $_POST['email'];
            $wiad = $_POST['wiad'];
            $sql = "INSERT INTO wiadomosci(`imie`,`email`,`pytanie`) VALUES ('" . $imie . "','" . $email . "','" . $wiad . "');";
            $que = mysqli_query($con,$sql);
        }
        if ($que) {
        echo "<script>alert('Wiadomość została wysłana do bazy danych!');</script>";
        } else {
        echo "<script>alert('Wystąpił błąd podczas zapisu do bazy danych!');</script>";
        }

        mysqli_close($con);
        ?>
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
</body>
</html>