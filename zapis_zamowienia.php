<?php
session_start();

//  --- 1.  Sprawdzenie Logowania ---
if (!isset($_SESSION['id_uzytkownika'])) {
    echo "Błąd: Użytkownik niezalogowany.";
    exit;  //  WAŻNE:  Zatrzymujemy skrypt, jeśli brak logowania
}

//  --- 2.  Połączenie z Bazą Danych ---
$host = "localhost";
$uzytkownik_db = "root";
$haslo_db = "";
$nazwa_bazy = "buty";

$polaczenie = new mysqli($host, $uzytkownik_db, $haslo_db, $nazwa_bazy);
if ($polaczenie->connect_error) {
    die("Błąd połączenia: " . $polaczenie->connect_error);  //  Zatrzymujemy skrypt w przypadku błędu połączenia
}

//  --- 3.  Pobranie i Sanityzacja Danych ---
$id_uzytkownika = $_SESSION['id_uzytkownika'];  //  Pobieramy ID użytkownika z sesji
$nazwa_produktu = filter_var($_POST['nazwa'], FILTER_SANITIZE_STRING);
$cena_produktu = filter_var($_POST['cena'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$zdjecie_produktu = filter_var($_POST['zdjecie'], FILTER_SANITIZE_URL);
$rozmiar_produktu = filter_var($_POST['rozmiar'], FILTER_SANITIZE_STRING);

//  --- 4.  Walidacja Danych ---
if (!$nazwa_produktu || !$cena_produktu || !$zdjecie_produktu || !$rozmiar_produktu || !is_numeric($cena_produktu)) {
    echo "Błąd: Nieprawidłowe dane.";
    exit;  //  Zatrzymujemy skrypt, jeśli dane są nieprawidłowe
}

//  --- 5.  Rozpoczęcie Transakcji ---
$polaczenie->begin_transaction();  //  Kluczowe dla spójności danych!

try {
    //  --- 6.1  Zapis do Tabeli 'zamowienia' ---
    $stmt_zamowienie = $polaczenie->prepare("INSERT INTO zamowienia (id_uzytkownika, kwota_calkowita) VALUES (?, ?)");
    $stmt_zamowienie->bind_param("id", $id_uzytkownika, $cena_produktu);
    $stmt_zamowienie->execute();

    if ($stmt_zamowienie->affected_rows == 0) {
        throw new Exception("Błąd podczas tworzenia zamówienia.");
    }

    $id_zamowienia = $polaczenie->insert_id;  //  Pobieramy ID NOWO utworzonego zamówienia
    $stmt_zamowienie->close();

    //  --- 6.2  Pobranie id_produktu ---
    //  ***WAŻNE***:  Zakładamy, że masz tabelę 'produkty' i 'nazwa' jest unikalna (lub masz inny unikalny identyfikator)
    $stmt_pobierz_id_produktu = $polaczenie->prepare("SELECT id_produktu FROM produkty WHERE nazwa = ?");
    $stmt_pobierz_id_produktu->bind_param("s", $nazwa_produktu);
    $stmt_pobierz_id_produktu->execute();
    $result_id_produktu = $stmt_pobierz_id_produktu->get_result();

    if ($result_id_produktu->num_rows == 0) {
        throw new Exception("Nie znaleziono produktu o nazwie: " . $nazwa_produktu);
    }

    $row_id_produktu = $result_id_produktu->fetch_assoc();
    $id_produktu = $row_id_produktu['id_produktu'];
    $stmt_pobierz_id_produktu->close();

    //  --- 6.3  Zapis do Tabeli 'elementy_zamowienia' ---
    $ilosc = 1;  //  Domyślnie 1 sztuka
    $stmt_element_zamowienia = $polaczenie->prepare("INSERT INTO elementy_zamowienia (id_zamowienia, id_produktu, ilosc, cena_jednostkowa) VALUES (?, ?, ?, ?)");
    $stmt_element_zamowienia->bind_param("iiid", $id_zamowienia, $id_produktu, $ilosc, $cena_produktu);
    $stmt_element_zamowienia->execute();

    if ($stmt_element_zamowienia->affected_rows == 0) {
        throw new Exception("Błąd podczas dodawania elementu zamówienia.");
    }
    $stmt_element_zamowienia->close();

    //  --- 7.  Zatwierdzenie Transakcji ---
    $polaczenie->commit();
    echo "<script>alert('Zamówienie złożone pomyślnie!'); window.location.href='index.php';</script>";

} catch (Exception $e) {
    //  --- 8.  Wycofanie Transakcji (w razie błędu) ---
    $polaczenie->rollback();
    echo "Błąd: " . $e->getMessage();
    error_log("Błąd podczas składania zamówienia: " . $e->getMessage() . "  SQL Error: " . $polaczenie->error);
}

//  --- 9.  Zamknięcie Połączenia ---
$polaczenie->close();
?>