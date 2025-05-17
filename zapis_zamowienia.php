<?php
error_log("DEBUG: Dane z koszyka: " . print_r($_POST, true));
echo "<pre>"; print_r($_POST); echo "</pre>";
session_start();

if (!isset($_SESSION['id_uzytkownika'])) {
    echo "Błąd: Użytkownik niezalogowany.";
    exit;
}

$host = "localhost";
$uzytkownik_db = "root";
$haslo_db = "";
$nazwa_bazy = "buty";

$polaczenie = new mysqli($host, $uzytkownik_db, $haslo_db, $nazwa_bazy);
if ($polaczenie->connect_error) {
    die("Błąd połączenia: " . $polaczenie->connect_error);
}

$id_uzytkownika = $_SESSION['username'];
$nazwy_produktow = isset($_POST['nazwa']) && is_array($_POST['nazwa']) ? $_POST['nazwa'] : [];
$ceny_produktow = isset($_POST['cena']) && is_array($_POST['cena']) ? $_POST['cena'] : [];
$zdjecia_produktow = isset($_POST['zdjecie']) && is_array($_POST['zdjecie']) ? $_POST['zdjecie'] : [];
$rozmiary_produktow = isset($_POST['rozmiar']) && is_array($_POST['rozmiar']) ? $_POST['rozmiar'] : []; // Dodano rozmiar

if (empty($nazwy_produktow) || empty($ceny_produktow) || empty($zdjecia_produktow) || empty($rozmiary_produktow)) { // Sprawdzamy też rozmiar
    echo "Błąd: Brak danych produktów do przetworzenia.";
    exit;
}

foreach ($ceny_produktow as $index => $cena) {
    if (!is_numeric($cena)) {
        echo "Błąd: Nieprawidłowa cena produktu.";
        exit;
    }
    $ceny_produktow[$index] = floatval($cena);
}

if (empty($nazwy_produktow)) {
    echo "Błąd: Koszyk jest pusty.";
    exit;
}

$polaczenie->begin_transaction();

try {
    $data_zamowienia = date("Y-m-d H:i:s");
    error_log("DEBUG: Data przed wstawieniem: " . $data_zamowienia);

    error_log("DEBUG: ID Uzytkownika z sesji: " . $_SESSION['id_uzytkownika']);
error_log("DEBUG: Zmienna id_klienta: " . $id_klienta);
    $stmt_zamowienie = $polaczenie->prepare("INSERT INTO zamowienia (id_klienta, data_zamowienia) VALUES (?, ?)");
    $stmt_zamowienie->bind_param("is", $id_uzytkownika, $data_zamowienia);
    $stmt_zamowienie->execute();
    $id_zamowienia = $polaczenie->insert_id;

    if ($polaczenie->errno) {
        throw new Exception("Błąd podczas zapisywania zamówienia: " . $polaczenie->error);
    }

    foreach ($nazwy_produktow as $index => $nazwa_produktu) {
        $cena_produktu = $ceny_produktow[$index];
        $id_produktu = 0; // Inicjalizacja
        $rozmiar = $rozmiary_produktow[$index] ?? null; // Pobierz rozmiar lub null, jeśli brak

        // Pobierz ID produktu na podstawie nazwy (zakładamy, że nazwa jest unikalna)
        $stmt_pobierz_id_produktu = $polaczenie->prepare("SELECT id_produktu FROM produkty WHERE nazwa = ?");
        $stmt_pobierz_id_produktu->bind_param("s", $nazwa_produktu);
        $stmt_pobierz_id_produktu->execute();
        $result_id_produktu = $stmt_pobierz_id_produktu->get_result();

        if ($result_id_produktu->num_rows == 0) {
            throw new Exception("Nie znaleziono produktu o nazwie: " . $nazwa_produktu);
        } elseif ($result_id_produktu->num_rows > 1) {
            error_log("UWAGA: Znaleziono wiele produktów o nazwie: " . $nazwa_produktu);
        }

        $row_id_produktu = $result_id_produktu->fetch_assoc();
        $id_produktu = $row_id_produktu['id_produktu'];
        $stmt_pobierz_id_produktu->close();

        // Zapis do Tabeli 'elementy_zamowienia'
        $ilosc = 1;
        $stmt_element_zamowienia = $polaczenie->prepare("INSERT INTO elementy_zamowienia (id_zamowienia, id_produktu, ilosc, cena_jednostkowa, id_klienta, rozmiar) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_element_zamowienia->bind_param("iiiidiv", $id_zamowienia, $id_produktu, $ilosc, $cena_produktu, $id_uzytkownika, $rozmiary_produktow);  // POPRAWIONY bind_param
        $stmt_element_zamowienia->execute();

        if ($stmt_element_zamowienia->affected_rows == 0) {
            throw new Exception("Błąd podczas zapisywania elementu zamówienia: " . $polaczenie->error);
        }
        $stmt_element_zamowienia->close();
    }

    $polaczenie->commit();
    echo "Zamówienie zostało pomyślnie zapisane.";
    // Możesz przekierować użytkownika na stronę potwierdzenia zamówienia
    // header("Location: potwierdzenie_zamowienia.php");
    exit;

} catch (Exception $e) {
    $polaczenie->rollback();
    echo "Błąd: " . $e->getMessage();
    error_log("Błąd transakcji: " . $e->getMessage());
}

$polaczenie->close();
?>