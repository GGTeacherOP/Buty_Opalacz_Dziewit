<?php
// Włączenie logowania błędów do pliku serwera.
// Ta linia zapisuje zawartość tablicy $_POST (dane przesłane z formularza) w logach serwera.
// print_r($_POST, true) konwertuje tablicę na czytelny string.
error_log("DEBUG: Dane z koszyka: " . print_r($_POST, true));

// Wyświetlenie zawartości tablicy $_POST na stronie w formacie preformatowanym (<pre>),
// co ułatwia debugowanie i sprawdzenie, jakie dane zostały przesłane.
echo "<pre>"; print_r($_POST); echo "</pre>";

// Rozpoczęcie sesji PHP. Sesje pozwalają na przechowywanie danych o użytkowniku między żądaniami.
session_start();

// Sprawdzenie, czy w sesji istnieje zmienna 'id_uzytkownika'.
// Jeśli nie istnieje, oznacza to, że użytkownik nie jest zalogowany.
if (!isset($_SESSION['id_uzytkownika'])) {
    // Wyświetlenie komunikatu o błędzie, jeśli użytkownik nie jest zalogowany.
    echo "Błąd: Użytkownik niezalogowany.";
    // Zakończenie wykonywania skryptu.
    exit;
}

// Definiowanie danych do połączenia z bazą danych MySQL.
$host = "localhost"; // Adres serwera bazy danych.
$uzytkownik_db = "root"; // Nazwa użytkownika bazy danych.
$haslo_db = ""; // Hasło użytkownika bazy danych (zazwyczaj puste w środowisku lokalnym).
$nazwa_bazy = "buty"; // Nazwa bazy danych, z którą chcemy się połączyć.

// Utworzenie nowego połączenia z bazą danych MySQL przy użyciu klasy mysqli.
$polaczenie = new mysqli($host, $uzytkownik_db, $haslo_db, $nazwa_bazy);

// Sprawdzenie, czy wystąpił błąd podczas łączenia z bazą danych.
if ($polaczenie->connect_error) {
    // Jeśli połączenie nie powiodło się, wyświetlenie komunikatu o błędzie i zakończenie skryptu.
    die("Błąd połączenia: " . $polaczenie->connect_error);
}

// Pobranie ID użytkownika z sesji. Zakładamy, że po zalogowaniu ID użytkownika jest przechowywane pod kluczem 'id_uzytkownika'.
$id_uzytkownika = $_SESSION['id_uzytkownika'];

// Pobranie danych produktów z tablicy $_POST. Użycie operatora warunkowego (ternary) i funkcji is_array()
// w celu zapewnienia, że zmienne są tablicami i nie są puste. Jeśli nie są tablicami lub nie istnieją, przypisana zostanie pusta tablica.
$nazwy_produktow = isset($_POST['nazwa']) && is_array($_POST['nazwa']) ? $_POST['nazwa'] : [];
$ceny_produktow = isset($_POST['cena']) && is_array($_POST['cena']) ? $_POST['cena'] : [];
$zdjecia_produktow = isset($_POST['zdjecie']) && is_array($_POST['zdjecie']) ? $_POST['zdjecie'] : [];
$rozmiary_produktow = isset($_POST['rozmiar']) && is_array($_POST['rozmiar']) ? $_POST['rozmiar'] : []; // Pobranie danych rozmiarów produktów.

// Sprawdzenie, czy tablice z danymi produktów są puste. Jeśli tak, wyświetlenie błędu i zakończenie skryptu.
// Dodano sprawdzenie rozmiarów produktów.
if (empty($nazwy_produktow) || empty($ceny_produktow) || empty($zdjecia_produktow) || empty($rozmiary_produktow)) {
    echo "Błąd: Brak danych produktów do przetworzenia.";
    exit;
}

// Iteracja po tablicy cen produktów w celu sprawdzenia, czy wszystkie ceny są liczbami.
foreach ($ceny_produktow as $index => $cena) {
    // Sprawdzenie, czy aktualna cena nie jest liczbą.
    if (!is_numeric($cena)) {
        echo "Błąd: Nieprawidłowa cena produktu.";
        exit;
    }
    // Konwersja poprawnej ceny na typ float.
    $ceny_produktow[$index] = floatval($cena);
}

// Dodatkowe sprawdzenie, czy koszyk nie jest pusty (redundantne, ale może być użyteczne w innych scenariuszach).
if (empty($nazwy_produktow)) {
    echo "Błąd: Koszyk jest pusty.";
    exit;
}

// Rozpoczęcie transakcji bazy danych. Transakcje zapewniają, że wiele operacji na bazie danych zostanie wykonanych jako jedna całość.
// Jeśli jedna z operacji zawiedzie, wszystkie zmiany zostaną cofnięte (rollback).
$polaczenie->begin_transaction();

try {
    // Pobranie aktualnej daty i czasu w formacie YYYY-MM-DD HH:MM:SS.
    $data_zamowienia = date("Y-m-d H:i:s");
    // Zapisanie daty zamówienia w logach serwera do debugowania.
    error_log("DEBUG: Data przed wstawieniem: " . $data_zamowienia);

    // Zapisanie ID użytkownika z sesji w logach serwera do debugowania.
    error_log("DEBUG: ID Uzytkownika z sesji: " . $_SESSION['id_uzytkownika']);
    // Zapisanie zmiennej $id_klienta w logach serwera do debugowania (zwróć uwagę, że ta zmienna nie jest nigdzie wcześniej zdefiniowana w tym skrypcie).
    error_log("DEBUG: Zmienna id_klienta: " . $id_klienta);

    // Przygotowanie zapytania SQL do wstawienia nowego zamówienia do tabeli 'zamowienia'.
    // Użycie prepared statements (parametryzowanych zapytań) dla bezpieczeństwa.
    $stmt_zamowienie = $polaczenie->prepare("INSERT INTO zamowienia (id_klienta, data_zamowienia) VALUES (?, ?)");
    // Powiązanie parametrów z zapytaniem SQL. "is" oznacza typy danych kolejnych parametrów:
    // i - integer (id_klienta)
    // s - string (data_zamowienia)
    $stmt_zamowienie->bind_param("is", $id_uzytkownika, $data_zamowienia);
    // Wykonanie przygotowanego zapytania SQL.
    $stmt_zamowienie->execute();
    // Pobranie ID ostatnio wstawionego rekordu (ID nowego zamówienia).
    $id_zamowienia = $polaczenie->insert_id;

    // Sprawdzenie, czy podczas zapisywania zamówienia wystąpił błąd.
    if ($polaczenie->errno) {
        // Jeśli wystąpił błąd, rzuć wyjątek z komunikatem o błędzie.
        throw new Exception("Błąd podczas zapisywania zamówienia: " . $polaczenie->error);
    }

    // Iteracja po tablicy nazw produktów w celu przetworzenia każdego produktu w zamówieniu.
    foreach ($nazwy_produktow as $index => $nazwa_produktu) {
        // Pobranie ceny aktualnego produktu z tablicy cen.
        $cena_produktu = $ceny_produktow[$index];
        // Inicjalizacja zmiennej $id_produktu.
        $id_produktu = 0;
        // Pobranie rozmiaru aktualnego produktu z tablicy rozmiarów lub ustawienie na null, jeśli brak.
        $rozmiar = $rozmiary_produktow[$index] ?? null;

        // Przygotowanie zapytania SQL do pobrania ID produktu na podstawie jego nazwy.
        // Zakładamy, że nazwa produktu jest unikalna w tabeli 'produkty'.
        $stmt_pobierz_id_produktu = $polaczenie->prepare("SELECT id_produktu FROM produkty WHERE nazwa = ?");
        // Powiązanie parametru z zapytaniem SQL. "s" oznacza typ danych parametru: string (nazwa_produktu).
        $stmt_pobierz_id_produktu->bind_param("s", $nazwa_produktu);
        // Wykonanie przygotowanego zapytania SQL.
        $stmt_pobierz_id_produktu->execute();
        // Pobranie wyniku zapytania.
        $result_id_produktu = $stmt_pobierz_id_produktu->get_result();

        // Sprawdzenie liczby wierszy zwróconych przez zapytanie o ID produktu.
        if ($result_id_produktu->num_rows == 0) {
            // Jeśli nie znaleziono produktu o danej nazwie, rzuć wyjątek.
            throw new Exception("Nie znaleziono produktu o nazwie: " . $nazwa_produktu);
        } elseif ($result_id_produktu->num_rows > 1) {
            // Jeśli znaleziono więcej niż jeden produkt o tej samej nazwie, zapisz ostrzeżenie w logach.
            error_log("UWAGA: Znaleziono wiele produktów o nazwie: " . $nazwa_produktu);
        }

        // Pobranie wiersza z wynikiem zapytania o ID produktu.
        $row_id_produktu = $result_id_produktu->fetch_assoc();
        // Przypisanie pobranego ID produktu do zmiennej $id_produktu.
        $id_produktu = $row_id_produktu['id_produktu'];
        // Zamknięcie przygotowanego zapytania.
        $stmt_pobierz_id_produktu->close();

        // Zapis do Tabeli 'elementy_zamowienia'
        $ilosc = 1; // Domyślna ilość produktu w zamówieniu.
        // Przygotowanie zapytania SQL do wstawienia elementu zamówienia do tabeli 'elementy_zamowienia'.
        $stmt_element_zamowienia = $polaczenie->prepare("INSERT INTO elementy_zamowienia (id_zamowienia, id_produktu, ilosc, cena_jednostkowa, id_klienta, rozmiar) VALUES (?, ?, ?, ?, ?, ?)");
        // Powiązanie parametrów z zapytaniem SQL. "iiiids" oznacza typy danych kolejnych parametrów:
        // i - integer (id_zamowienia, id_produktu, ilosc, id_klienta)
        // d - double (cena_jednostkowa)
        // s - string (rozmiar)
        $stmt_element_zamowienia->bind_param("iiiids", $id_zamowienia, $id_produktu, $ilosc, $cena_produktu, $id_uzytkownika, $rozmiar);
        // Wykonanie przygotowanego zapytania SQL.
        $stmt_element_zamowienia->execute();

        // Sprawdzenie, czy wstawienie elementu zamówienia powiodło się.
        if ($stmt_element_zamowienia->affected_rows == 0) {
            // Jeśli nie dodano żadnego wiersza, rzuć wyjątek.
            throw new Exception("Błąd podczas zapisywania elementu zamówienia: " . $polaczenie->error);
        }
        // Zamknięcie przygotowanego zapytania.
        $stmt_element_zamowienia->close();
    }

    // Zatwierdzenie transakcji. Wszystkie operacje na bazie danych zostały wykonane pomyślnie.
    $polaczenie->commit();
    // Wyświetlenie komunikatu o pomyślnym zapisaniu zamówienia.
    echo "Zamówienie zostało pomyślnie zapisane.";
    // Możliwe przekierowanie użytkownika na stronę potwierdzenia zamówienia.
    // header("Location: potwierdzenie_zamowienia.php");
    exit;

} catch (Exception $e) {
    // W przypadku wystąpienia wyjątku (błędu), wycofaj wszystkie zmiany dokonane w ramach transakcji.
    $polaczenie->rollback();
    // Wyświetlenie komunikatu o błędzie.
    echo "Błąd: " . $e->getMessage();
    // Zapisanie informacji o błędzie transakcji w logach serwera.
    error_log("Błąd transakcji: " . $e->getMessage());
}

// Zamknięcie połączenia z bazą danych.
$polaczenie->close();
?>