<?php
session_start();
include 'auth_utils.php';

$host = 'localhost';
$uzytkownik_db = 'root';
$haslo_db = '';
$nazwa_bazy = 'buty';

$polaczenie = new mysqli($host, $uzytkownik_db, $haslo_db, $nazwa_bazy);
if ($polaczenie->connect_error) {
    die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error);
}

$zalogowany = isset($_SESSION['username']);
$rola = $_SESSION['rola'] ?? 'gość';
$id_klienta = $_SESSION['id_uzytkownika'] ?? null;

// Pobierz koszyk z bazy danych
function pobierz_koszyk_z_bazy($polaczenie, $id_klienta) {
    $koszyk = [];
    if ($id_klienta) {
        $sql = "SELECT k.*, p.nazwa, p.cena, p.url_zdjecia AS zdjecie
                FROM koszyki k
                JOIN produkty p ON k.id_produktu = p.id_produktu
                WHERE k.id_klienta = ?";
        $stmt = $polaczenie->prepare($sql);
        $stmt->bind_param("i", $id_klienta);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $koszyk[] = [
                'id_produktu' => $row['id_produktu'],
                'nazwa' => $row['nazwa'],
                'cena' => $row['cena'],
                'zdjecie' => $row['zdjecie'],
                'rozmiar' => $row['rozmiar'],
                'ilosc' => $row['ilosc'],
                'id_koszyka' => $row['id_koszyka']
            ];
        }
    }
    return $koszyk;
}

function dodaj_do_koszyka_w_bazie($polaczenie, $id_klienta, $id_produktu, $rozmiar, $ilosc = 1) {
    $sql = "INSERT INTO koszyki (id_klienta, id_produktu, rozmiar, ilosc) VALUES (?, ?, ?, ?)";
    $stmt = $polaczenie->prepare($sql);
    $stmt->bind_param("iisi", $id_klienta, $id_produktu, $rozmiar, $ilosc); // ✅ MOD: poprawiony typ 's' dla rozmiaru
    $stmt->execute();
    return $polaczenie->insert_id;
}

function aktualizuj_ilosc_w_bazie($polaczenie, $id_koszyka, $ilosc) {
    $sql = "UPDATE koszyki SET ilosc = ? WHERE id_koszyka = ?";
    $stmt = $polaczenie->prepare($sql);
    $stmt->bind_param("ii", $ilosc, $id_koszyka);
    $stmt->execute();
}

function usun_z_koszyka_w_bazie($polaczenie, $id_koszyka) {
    $sql = "DELETE FROM koszyki WHERE id_koszyka = ?";
    $stmt = $polaczenie->prepare($sql);
    $stmt->bind_param("i", $id_koszyka);
    $stmt->execute();
}

if ($zalogowany) {
    $_SESSION['koszyk'] = pobierz_koszyk_z_bazy($polaczenie, $id_klienta);
}
// Dodawanie do koszyka
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dodaj_do_koszyka'])) {
    if (!$zalogowany) {
        header('Location: login.php');
        exit;
    }

    $id_produktu = intval($_POST['id_produktu']);
    $nazwa = $_POST['nazwa'];
    $cena = floatval($_POST['cena']);
    $zdjecie = $_POST['zdjecie'];
    $rozmiar = trim($_POST['rozmiar']);

    $produkt_istnieje = false;
    foreach ($_SESSION['koszyk'] as $key => $item) {
        if ($item['id_produktu'] == $id_produktu && $item['rozmiar'] == $rozmiar) {
            $_SESSION['koszyk'][$key]['ilosc']++;
            $produkt_istnieje = true;
            aktualizuj_ilosc_w_bazie($polaczenie, $item['id_koszyka'], $_SESSION['koszyk'][$key]['ilosc']);
            break;
        }
    }

    if (!$produkt_istnieje) {
        $id_koszyka = dodaj_do_koszyka_w_bazie($polaczenie, $id_klienta, $id_produktu, $rozmiar);
        $nowy_produkt = [
            'id_produktu' => $id_produktu,
            'nazwa' => $nazwa,
            'cena' => $cena,
            'zdjecie' => $zdjecie,
            'rozmiar' => $rozmiar,
            'ilosc' => 1,
            'id_koszyka' => $id_koszyka
        ];
        $_SESSION['koszyk'][] = $nowy_produkt;
    }

    header('Location: koszyk.php');
    exit;
}

// Aktualizacja ilości
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aktualizuj_koszyk']) && $zalogowany) {
    if (isset($_POST['ilosc']) && is_array($_POST['ilosc'])) {
        foreach ($_POST['ilosc'] as $i => $ilosc) {
            $ilosc = max(1, intval($ilosc));
            $_SESSION['koszyk'][$i]['ilosc'] = $ilosc;
            aktualizuj_ilosc_w_bazie($polaczenie, $_SESSION['koszyk'][$i]['id_koszyka'], $ilosc);
        }
    }
    header('Location: koszyk.php');
    exit;
}

// Usuwanie produktu
if (isset($_GET['usun']) && $zalogowany) {
    $indeks = intval($_GET['usun']);
    if (isset($_SESSION['koszyk'][$indeks])) {
        usun_z_koszyka_w_bazie($polaczenie, $_SESSION['koszyk'][$indeks]['id_koszyka']);
        unset($_SESSION['koszyk'][$indeks]);
        $_SESSION['koszyk'] = array_values($_SESSION['koszyk']);
    }
    header('Location: koszyk.php');
    exit;
}

function oblicz_sume_koszyka() {
    $suma = 0;
    if (isset($_SESSION['koszyk'])) {
        foreach ($_SESSION['koszyk'] as $produkt) {
    $suma += (float)$produkt['cena'] * (int)$produkt['ilosc'];
}

    }
    return $suma;
}

$koszyk = $_SESSION['koszyk'] ?? [];

// ✅ MOD: Poprawiony zapis zamówienia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kup_teraz']) && $zalogowany) {
    $id_uzytkownika = $_SESSION['id_uzytkownika'];
    $data_zamowienia = date('Y-m-d H:i:s');
    $suma_zamowienia = oblicz_sume_koszyka();

    $stmt_zamowienie = $polaczenie->prepare("INSERT INTO zamowienia (id_klienta, data_zamowienia, kwota_calkowita) VALUES (?, ?, ?)");
    $stmt_zamowienie->bind_param("isd", $id_uzytkownika, $data_zamowienia, $suma_zamowienia);
    $stmt_zamowienie->execute();
    $id_zamowienia = $polaczenie->insert_id;
    $stmt_zamowienie->close();

    foreach ($_SESSION['koszyk'] as $produkt) {
        $id_produktu = $produkt['id_produktu'];
        $ilosc = $produkt['ilosc'];
        $cena_jednostkowa = $produkt['cena'];
        $rozmiar = $produkt['rozmiar'];
        $id_klienta = $id_uzytkownika;

        $stmt_element = $polaczenie->prepare("
            INSERT INTO elementy_zamowienia (id_zamowienia, id_produktu, ilosc, cena_jednostkowa, id_klienta, rozmiar)
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_element->bind_param("iiidis", $id_zamowienia, $id_produktu, $ilosc, $cena_jednostkowa, $id_klienta, $rozmiar);
        $stmt_element->execute();
        $stmt_element->close();
    }

    // ✅ MOD: Usunięcie koszyka użytkownika z bazy
    $stmt_clear = $polaczenie->prepare("DELETE FROM koszyki WHERE id_klienta = ?");
    $stmt_clear->bind_param("i", $id_uzytkownika);
    $stmt_clear->execute();
    $stmt_clear->close();

    $_SESSION['koszyk'] = [];

    echo "<script>alert('Dziękujemy za złożenie zamówienia!'); window.location.href='index.php';</script>";
    exit;
}
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

        <main>
            <div class="koszyk-container">
                <h1>Twój koszyk</h1>

                <?php if (!empty($koszyk)): ?>
                    <form method="post" action="koszyk.php" id="aktualizacja-koszyka-form">
                        <?php foreach ($koszyk as $i => $produkt): ?>
                            <div class="produkt-w-koszyku">
                                <div class="produkt-info">
                                    <img src="<?= htmlspecialchars($produkt['zdjecie']) ?>"
                                         alt="<?= htmlspecialchars($produkt['nazwa']) ?>">
                                    <div class="produkt-dane">
                                        <span><?= htmlspecialchars($produkt['nazwa']) ?></span>
                                        <span>Rozmiar: <?= htmlspecialchars($produkt['rozmiar']) ?></span>
                                        <span>Cena: <?= number_format($produkt['cena'], 2) ?> zł</span>
                                        <label>
                                            Ilość:
                                            <input type="number" name="ilosc[<?= $i ?>]" value="<?= $produkt['ilosc'] ?>"
                                                   min="1" class="ilosc-input">
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
                            <button type="submit" name="kup_teraz" class="kup-btn">Kup Teraz</button>
                        </div>
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







 
         