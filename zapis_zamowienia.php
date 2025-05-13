<?php
session_start();

if (!isset($_SESSION['id_uzytkownika'])) {
    echo "Błąd: Użytkownik niezalogowany.";
    exit;
}

if (!isset($_POST['potwierdz_zamowienie'])) {  // ZMIANA: Sprawdź, czy przyszło z formularza zamówienia
    echo "Błąd: Nieprawidłowe żądanie.";
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

$id_uzytkownika = $_SESSION['id_uzytkownika'];
$polaczenie->begin_transaction();

try {
    // Oblicz całkowitą kwotę zamówienia
    $kwota_calkowita = 0;
    if (isset($_POST['cena']) && is_array($_POST['cena']) && isset($_POST['ilosc']) && is_array($_POST['ilosc'])) {
        $ceny = $_POST['cena'];
        $ilosci = $_POST['ilosc'];

        if (count($ceny) !== count($ilosci)) {
            throw new Exception("Błąd: Liczba cen i ilości produktów się nie zgadza.");
        }

        for ($i = 0; $i < count($ceny); $i++) {
            $cena = (float)$ceny[$i];
            $ilosc = (int)$ilosci[$i];
            $kwota_calkowita += $cena * $ilosc;
        }
    } else {
        throw new Exception("Błąd: Brak danych o cenach lub ilościach.");
    }

    // Zapis do tabeli zamowienia
    $stmt_zamowienie = $polaczenie->prepare("INSERT INTO zamowienia (id_uzytkownika, kwota_calkowita) VALUES (?, ?)");
    $stmt_zamowienie->bind_param("id", $id_uzytkownika, $kwota_calkowita);
    $stmt_zamowienie->execute();
    $id_zamowienia = $polaczenie->insert_id;
    $stmt_zamowienie->close();

    // Zapis każdego produktu do elementy_zamowienia
    if (isset($_POST['nazwa']) && is_array($_POST['nazwa']) && isset($_POST['ilosc']) && is_array($_POST['ilosc'])) {
        $nazwy = $_POST['nazwa'];
        $ilosci = $_POST['ilosc'];
        $ceny = $_POST['cena'];

        if (count($nazwy) !== count($ilosci) || count($nazwy) !== count($ceny)) {
            throw new Exception("Błąd: Niezgodność liczby produktów, cen i ilości.");
        }

        for ($i = 0; $i < count($nazwy); $i++) {
            $nazwa_produktu = filter_var($nazwy[$i], FILTER_SANITIZE_STRING);
            $ilosc_produktu = (int)$ilosci[$i];
            $cena_produktu = (float)$ceny[$i];

            // Pobierz id_produktu na podstawie nazwy
            $stmt_pobierz_id_produktu = $polaczenie->prepare("SELECT id_produktu FROM produkty WHERE nazwa = ?");
            $stmt_pobierz_id_produktu->bind_param("s", $nazwa_produktu);
            $stmt_pobierz_id_produktu->execute();
            $result_id_produktu = $stmt_pobierz_id_produktu->get_result();

            if ($result_id_produktu->num_rows > 0) {
                $row_id_produktu = $result_id_produktu->fetch_assoc();
                $id_produktu = $row_id_produktu['id_produktu'];

                $stmt_element_zamowienia = $polaczenie->prepare("INSERT INTO elementy_zamowienia (id_zamowienia, id_produktu, ilosc, cena_jednostkowa) VALUES (?, ?, ?, ?)");
                $stmt_element_zamowienia->bind_param("iiid", $id_zamowienia, $id_produktu, $ilosc_produktu, $cena_produktu);
                $stmt_element_zamowienia->execute();
                $stmt_element_zamowienia->close();
            } else {
                error_log("Błąd: Nie znaleziono produktu o nazwie: " . $nazwa_produktu);
            }
            $stmt_pobierz_id_produktu->close();
        }
    } else {
        throw new Exception("Błąd: Brak danych o nazwach produktów, cenach lub ilościach.");
    }

    $polaczenie->commit();
    unset($_SESSION['koszyk']);
    echo "<script>alert('Zamówienie złożone pomyślnie!'); window.location.href='index.php';</script>";

} catch (Exception $e) {
    $polaczenie->rollback();
    echo "Błąd: " . $e->getMessage();
    error_log("Błąd podczas składania zamówienia: " . $e->getMessage() . " SQL Error: " . $polaczenie->error);
}

$polaczenie->close();
?>