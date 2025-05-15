<?php
session_start();
include 'auth_utils.php';
$zalogowany = isset($_SESSION['username']);
$rola = $_SESSION['rola'] ?? 'gość';  // Domyślnie 'gość' dla niezalogowanych

// Dodawanie produktu do koszyka
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dodaj_do_koszyka'])) {
    $id_produktu = intval($_POST['id_produktu']);  // Zmiana: Zawsze integer
    $nazwa = $_POST['nazwa'];
    $cena = floatval($_POST['cena']);   // Zmiana: Zawsze float
    $zdjecie = $_POST['zdjecie'];
    $rozmiar = trim($_POST['rozmiar']);  // Zmiana: Usuń spacje

    $produkt = array(
        'id' => $id_produktu,
        'nazwa' => $nazwa,
        'cena' => $cena,
        'zdjecie' => $zdjecie,
        'rozmiar' => $rozmiar,
        'ilosc' => 1
    );

    if (!isset($_SESSION['koszyk'])) {
        $_SESSION['koszyk'] = array();
    }

    $produkt_istnieje = false;
    foreach ($_SESSION['koszyk'] as $key => $item) {
        if (intval($item['id']) === $id_produktu && trim($item['rozmiar']) === $rozmiar) {  // Zmiana: Porównuj jako int i usuń spacje
            $_SESSION['koszyk'][$key]['ilosc']++;
            $produkt_istnieje = true;
            break;
        }
    }

    if (!$produkt_istnieje) {
        $_SESSION['koszyk'][] = $produkt;
    }

    header('Location: koszyk.php');
    exit;
}



// Aktualizacja ilości produktów
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['aktualizuj_koszyk'])) {
    if (isset($_POST['ilosc'])) {
        foreach ($_POST['ilosc'] as $index => $nowa_ilosc) {
            $nowa_ilosc = intval($nowa_ilosc);
            if ($nowa_ilosc > 0 && isset($_SESSION['koszyk'][$index])) {
                $_SESSION['koszyk'][$index]['ilosc'] = $nowa_ilosc;
            }
        }
    }
    header('Location: koszyk.php');
    exit;
}

// Usuwanie produktu
if (isset($_GET['usun'])) {
    $id = intval($_GET['usun']);
    if (isset($_SESSION['koszyk'][$id])) {
        unset($_SESSION['koszyk'][$id]);
        $_SESSION['koszyk'] = array_values($_SESSION['koszyk']);
    }
    header('Location: koszyk.php'); // WAŻNE: Przekieruj, aby zaktualizować sumę
    exit;
}

// Funkcja obliczająca sumę koszyka (zostawiamy tylko jedną deklarację)
function oblicz_sume_koszyka() {
    $suma = 0;
    if (!empty($_SESSION['koszyk'])) {
        foreach ($_SESSION['koszyk'] as $produkt) {
            $suma += (float)$produkt['cena'] * (int)$produkt['ilosc'];
        }
    }
    return $suma;
}

$suma = oblicz_sume_koszyka(); // Oblicz sumę przy załadowaniu strony


?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Twój Koszyk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Style CSS pozostają bez zmian */
        .koszyk-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .koszyk-container h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .produkt-w-koszyku {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }

        .produkt-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .produkt-info img {
            width: 100px;
            border-radius: 8px;
            background-color: #f8f8f8;
        }

        .produkt-dane {
            display: flex;
            flex-direction: column;
        }

        .produkt-dane span {
            font-size: 1.1rem;
        }

        .usun-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .usun-btn:hover {
            background-color: #c82333;
        }

        .pusty {
            text-align: center;
            font-size: 1.1rem;
            color: #666;
            padding: 30px 0;
        }

        .suma-container {
            text-align: right;
            margin-top: 30px;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .kup-btn {
            display: inline-block;
            background-color: #0c7a43;
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .kup-btn:hover {
            background-color: #095c30;
        }

        .ilosc-input {
            width: 50px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <header>
            <a href="index.php">Strona Główna</a>
            <a href="sklep.php">Sklep</a>
            <a href="koszyk.php" class="active">Koszyk</a>
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

        <main>
            <div class="koszyk-container">
                <h1>Twój koszyk</h1>

      <?php if (!empty($_SESSION['koszyk'])): ?>
    <form method="post" action="koszyk.php" id="aktualizacja-koszyka-form">
        <?php foreach ($_SESSION['koszyk'] as $i => $produkt): ?>
            <div class="produkt-w-koszyku">
                <div class="produkt-info">
                    <img src="<?= htmlspecialchars($produkt['zdjecie']) ?>" alt="<?= htmlspecialchars($produkt['nazwa']) ?>">
                    <div class="produkt-dane">
                        <span><?= htmlspecialchars($produkt['nazwa']) ?></span>
                        <span>Rozmiar: <?= htmlspecialchars($produkt['rozmiar']) ?></span>
                        <span>Cena: <?= number_format($produkt['cena'], 2) ?> zł</span>
                        <label>
                            Ilość:
                            <input type="number" name="ilosc[<?= $i ?>]" value="<?= $produkt['ilosc'] ?>" min="1" class="ilosc-input">
                        </label>
                    </div>
                </div>
                <div>
                    <button type="button" onclick="usunProdukt(<?= $i ?>)" class="usun-btn">Usuń</button>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="suma-container">
            Suma: <?= number_format(oblicz_sume_koszyka(), 2) ?> zł
            <br>
            <button type="submit" name="aktualizuj_koszyk" value="1" class="kup-btn">Aktualizuj koszyk</button>
            <button type="button" onclick="document.getElementById('zapis-zamowienia-form').submit()" class="kup-btn">Przejdź do płatności</button>
        </div>
    </form>
    
    <form method="post" action="zapis_zamowienia.php" id="zapis-zamowienia-form">
        <input type="hidden" name="potwierdz_zamowienie" value="1">
        <?php foreach ($_SESSION['koszyk'] as $i => $produkt): ?>
            <input type="hidden" name="nazwa[]" value="<?= htmlspecialchars($produkt['nazwa']) ?>">
            <input type="hidden" name="cena[]" value="<?= htmlspecialchars($produkt['cena']) ?>">
            <input type="hidden" name="ilosc[]" value="<?= htmlspecialchars($produkt['ilosc']) ?>">
        <?php endforeach; ?>
    </form>
<?php else: ?>
    <p class="pusty">Koszyk jest pusty.</p>
<?php endif; ?>

            </div>
        </main>

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
    function usunProdukt(indeks) {
        if (confirm('Czy na pewno chcesz usunąć ten produkt z koszyka?')) {
            window.location.href = 'koszyk.php?usun=' + indeks;
        }
    }
    </script>

</body>

</html>