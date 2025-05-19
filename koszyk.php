<?php
session_start(); // Rozpoczęcie sesji PHP, umożliwiającej przechowywanie danych użytkownika.
include 'auth_utils.php'; // Dołączenie pliku z funkcjami autoryzacji (np. sprawdzania ról).

// Dane do połączenia z bazą danych.
$host = 'localhost';
$uzytkownik_db = 'root';
$haslo_db = '';
$nazwa_bazy = 'buty';

// Nawiązanie połączenia z bazą danych MySQL.
$polaczenie = new mysqli($host, $uzytkownik_db, $haslo_db, $nazwa_bazy);
// Sprawdzenie, czy wystąpił błąd podczas łączenia z bazą danych.
if ($polaczenie->connect_error) {
    die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error); // Wyświetlenie błędu i zatrzymanie skryptu.
}

// Sprawdzenie, czy użytkownik jest zalogowany poprzez istnienie zmiennej 'username' w sesji.
$zalogowany = isset($_SESSION['username']);
// Pobranie roli użytkownika z sesji. Jeśli nie istnieje, ustawiana jest domyślna wartość 'gość'.
$rola = $_SESSION['rola'] ?? 'gość';
// Pobranie ID zalogowanego użytkownika z sesji. Jeśli nie istnieje, ustawiana jest wartość null.
$id_klienta = $_SESSION['id_uzytkownika'] ?? null;

// Funkcja do pobierania zawartości koszyka użytkownika z bazy danych.
function pobierz_koszyk_z_bazy($polaczenie, $id_klienta) {
    $koszyk = []; // Inicjalizacja pustej tablicy na koszyk.
    if ($id_klienta) { // Sprawdzenie, czy ID klienta jest dostępne (użytkownik zalogowany).
        // Zapytanie SQL pobierające dane koszyka wraz z informacjami o produkcie.
        $sql = "SELECT k.*, p.nazwa, p.cena, p.url_zdjecia AS zdjecie
                    FROM koszyki k
                    JOIN produkty p ON k.id_produktu = p.id_produktu
                    WHERE k.id_klienta = ?";
        $stmt = $polaczenie->prepare($sql); // Przygotowanie zapytania SQL.
        $stmt->bind_param("i", $id_klienta); // Powiązanie ID klienta z parametrem zapytania.
        $stmt->execute(); // Wykonanie zapytania.
        $result = $stmt->get_result(); // Pobranie wyników zapytania.
        // Iteracja po każdym wierszu wyniku.
        while ($row = $result->fetch_assoc()) {
            // Dodanie danych produktu do tablicy koszyka.
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
    return $koszyk; // Zwrócenie tablicy z zawartością koszyka.
}

// Funkcja do dodawania produktu do koszyka w bazie danych.
function dodaj_do_koszyka_w_bazie($polaczenie, $id_klienta, $id_produktu, $rozmiar, $ilosc = 1) {
    // Zapytanie SQL wstawiające nowy produkt do koszyka.
    $sql = "INSERT INTO koszyki (id_klienta, id_produktu, rozmiar, ilosc) VALUES (?, ?, ?, ?)";
    $stmt = $polaczenie->prepare($sql); // Przygotowanie zapytania.
    $stmt->bind_param("iisi", $id_klienta, $id_produktu, $rozmiar, $ilosc); // Powiązanie parametrów.
    $stmt->execute(); // Wykonanie zapytania.
    return $polaczenie->insert_id; // Zwrócenie ID wstawionego wiersza.
}

// Funkcja do aktualizacji ilości produktu w koszyku w bazie danych.
function aktualizuj_ilosc_w_bazie($polaczenie, $id_koszyka, $ilosc) {
    // Zapytanie SQL aktualizujące ilość produktu w koszyku.
    $sql = "UPDATE koszyki SET ilosc = ? WHERE id_koszyka = ?";
    $stmt = $polaczenie->prepare($sql); // Przygotowanie zapytania.
    $stmt->bind_param("ii", $ilosc, $id_koszyka); // Powiązanie parametrów.
    $stmt->execute(); // Wykonanie zapytania.
}

// Funkcja do usuwania produktu z koszyka w bazie danych.
function usun_z_koszyka_w_bazie($polaczenie, $id_koszyka) {
    // Zapytanie SQL usuwające produkt z koszyka.
    $sql = "DELETE FROM koszyki WHERE id_koszyka = ?";
    $stmt = $polaczenie->prepare($sql); // Przygotowanie zapytania.
    $stmt->bind_param("i", $id_koszyka); // Powiązanie parametru.
    $stmt->execute(); // Wykonanie zapytania.
}

// Jeśli użytkownik jest zalogowany, pobierz jego koszyk z bazy danych i zapisz w sesji.
if ($zalogowany) {
    $_SESSION['koszyk'] = pobierz_koszyk_z_bazy($polaczenie, $id_klienta);
}

// Obsługa dodawania produktu do koszyka.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dodaj_do_koszyka'])) {
    if (!$zalogowany) { // Jeśli użytkownik nie jest zalogowany, przekieruj na stronę logowania.
        header('Location: login.php');
        exit;
    }

    // Pobranie danych produktu z formularza i zabezpieczenie.
    $id_produktu = intval($_POST['id_produktu']);
    $nazwa = $_POST['nazwa'];
    $cena = floatval($_POST['cena']);
    $zdjecie = $_POST['zdjecie'];
    $rozmiar = trim($_POST['rozmiar']);

    $produkt_istnieje = false; // Flaga informująca, czy produkt o danym rozmiarze jest już w koszyku.
    // Sprawdzenie, czy dany produkt o tym samym rozmiarze już istnieje w koszyku w sesji.
    foreach ($_SESSION['koszyk'] as $key => $item) {
        if ($item['id_produktu'] == $id_produktu && $item['rozmiar'] == $rozmiar) {
            $_SESSION['koszyk'][$key]['ilosc']++; // Zwiększenie ilości istniejącego produktu.
            $produkt_istnieje = true;
            aktualizuj_ilosc_w_bazie($polaczenie, $item['id_koszyka'], $_SESSION['koszyk'][$key]['ilosc']); // Aktualizacja ilości w bazie.
            break;
        }
    }

    // Jeśli produkt nie istnieje w koszyku, dodaj go.
    if (!$produkt_istnieje) {
        $id_koszyka = dodaj_do_koszyka_w_bazie($polaczenie, $id_klienta, $id_produktu, $rozmiar); // Dodanie do bazy.
        $_SESSION['koszyk'][] = [ // Dodanie do sesji.
            'id_produktu' => $id_produktu,
            'nazwa' => $nazwa,
            'cena' => $cena,
            'zdjecie' => $zdjecie,
            'rozmiar' => $rozmiar,
            'ilosc' => 1,
            'id_koszyka' => $id_koszyka
        ];
    }

    header('Location: koszyk.php'); // Przekierowanie na stronę koszyka.
    exit;
}

// Obsługa aktualizacji ilości produktów w koszyku.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aktualizuj_koszyk']) && $zalogowany) {
    // Sprawdzenie, czy przesłano tablicę z ilościami.
    if (isset($_POST['ilosc']) && is_array($_POST['ilosc'])) {
        // Iteracja po każdej ilości w przesłanej tablicy.
        foreach ($_POST['ilosc'] as $i => $ilosc) {
            $ilosc = max(1, intval($ilosc)); // Zapewnienie, że ilość jest co najmniej 1 i jest liczbą całkowitą.
            $_SESSION['koszyk'][$i]['ilosc'] = $ilosc; // Aktualizacja ilości w sesji.
            aktualizuj_ilosc_w_bazie($polaczenie, $_SESSION['koszyk'][$i]['id_koszyka'], $ilosc); // Aktualizacja ilości w bazie.
        }
    }
    header('Location: koszyk.php'); // Przekierowanie na stronę koszyka.
    exit;
}

// Obsługa usuwania produktu z koszyka.
if (isset($_GET['usun']) && $zalogowany) {
    $indeks = intval($_GET['usun']); // Pobranie indeksu produktu do usunięcia.
    // Sprawdzenie, czy dany indeks istnieje w koszyku w sesji.
    if (isset($_SESSION['koszyk'][$indeks])) {
        usun_z_koszyka_w_bazie($polaczenie, $_SESSION['koszyk'][$indeks]['id_koszyka']); // Usunięcie z bazy.
        unset($_SESSION['koszyk'][$indeks]); // Usunięcie z sesji.
        $_SESSION['koszyk'] = array_values($_SESSION['koszyk']); // Resetowanie indeksów tablicy koszyka w sesji.
    }
    header('Location: koszyk.php'); // Przekierowanie na stronę koszyka.
    exit;
}

// Funkcja do obliczania sumy wartości produktów w koszyku.
function oblicz_sume_koszyka() {
    $suma = 0; // Inicjalizacja sumy.
    if (isset($_SESSION['koszyk'])) { // Sprawdzenie, czy koszyk istnieje w sesji.
        // Iteracja po każdym produkcie w koszyku.
        foreach ($_SESSION['koszyk'] as $produkt) {
            $suma += (float)$produkt['cena'] * (int)$produkt['ilosc']; // Dodanie do sumy ceny pomnożonej przez ilość.
        }
    }
    return $suma; // Zwrócenie obliczonej sumy.
}

// Pobranie koszyka z sesji (jeśli istnieje) do lokalnej zmiennej.
$koszyk = $_SESSION['koszyk'] ?? [];

// Obsługa procesu zakupu (kliknięcie "Kup Teraz").
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kup_teraz']) && $zalogowany) {
    $id_uzytkownika = $_SESSION['id_uzytkownika']; // Pobranie ID zalogowanego użytkownika.
    $data_zamowienia = date('Y-m-d H:i:s'); // Pobranie aktualnej daty i czasu zamówienia.
    $suma_zamowienia = oblicz_sume_koszyka(); // Obliczenie całkowitej sumy zamówienia.

    // Wstawienie nowego zamówienia do tabeli 'zamowienia'.
    $stmt_zamowienie = $polaczenie->prepare("INSERT INTO zamowienia (id_klienta, data_zamowienia, kwota_calkowita) VALUES (?, ?, ?)");
    $stmt_zamowienie->bind_param("isd", $id_uzytkownika, $data_zamowienia, $suma_zamowienia);
    $stmt_zamowienie->execute();
    $id_zamowienia = $polaczenie->insert_id; // Pobranie ID wstawionego zamówienia.
    $stmt_zamowienie->close();

    // Iteracja po każdym produkcie w koszyku w celu dodania go do 'elementy_zamowienia'.
    foreach ($_SESSION['koszyk'] as $produkt) {
        $stmt_element = $polaczenie->prepare("
            INSERT INTO elementy_zamowienia (id_zamowienia, id_produktu, ilosc, cena_jednostkowa, id_klienta, rozmiar)
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_element->bind_param("iiidis", $id_zamowienia, $produkt['id_produktu'], $produkt['ilosc'], $produkt['cena'], $id_uzytkownika, $produkt['rozmiar']);
        $stmt_element->execute();
        $stmt_element->close();
    }

    // Wyczyszczenie koszyka użytkownika w bazie danych.
    $stmt_clear = $polaczenie->prepare("DELETE FROM koszyki WHERE id_klienta = ?");
    $stmt_clear->bind_param("i", $id_uzytkownika);
    $stmt_clear->execute();
    $stmt_clear->close();

    // Wyczyszczenie koszyka w sesji.
    $_SESSION['koszyk'] = [];

    // ✅ Niestandardowy alert zamiast alert() - Wyświetlenie komunikatu o pomyślnym złożeniu zamówienia.
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Dziękujemy</title>
    <style>
        .alert-box {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #0c7a43;
            color: white;
            padding: 30px 40px;
            font-size: 1.3rem;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            animation: fadeIn 0.5s ease;
            z-index: 9999;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translate(-50%, -60%); }
            to { opacity: 1; transform: translate(-50%, -50%); }
        }
    </style>
    </head><body>
    <div class="alert-box">Dziękujemy za złożenie zamówienia!<br>Przekierowuję na stronę główną...</div>
    <script>
        setTimeout(() => { window.location.href = "index.php"; }, 2500); // Przekierowanie na stronę główną po 2.5 sekundy.
    </script>
    </body></html>';
    exit; // Zakończenie skryptu po złożeniu zamówienia.
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
