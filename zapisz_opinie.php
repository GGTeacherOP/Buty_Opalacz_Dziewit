<?php
// Rozpocznij sesję, aby móc korzystać ze zmiennych sesyjnych, jeśli zajdzie taka potrzeba.
session_start();
// Dołącz plik 'auth_utils.php', który prawdopodobnie zawiera różne funkcje związane z autentykacją.
// W tym kodzie konkretnie nie używasz funkcji z tego pliku, ale jest on dołączany.
include 'auth_utils.php';

// Zdefiniuj stałe lub zmienne przechowujące dane do połączenia z bazą danych.
$host = "localhost"; // Adres serwera bazy danych.
$uzytkownik_db = "root"; // Nazwa użytkownika bazy danych.
$haslo_db = ""; // Hasło użytkownika bazy danych (w tym przypadku puste).
$nazwa_bazy = "buty"; // Nazwa bazy danych, z którą chcemy się połączyć.

// Utwórz nowe połączenie z bazą danych MySQL przy użyciu klasy mysqli.
$polaczenie = new mysqli($host, $uzytkownik_db, $haslo_db, $nazwa_bazy);

// Sprawdź, czy wystąpił błąd podczas próby połączenia z bazą danych.
if ($polaczenie->connect_error) {
    // Jeśli połączenie nie powiodło się, wyświetl komunikat o błędzie i zakończ wykonywanie skryptu.
    die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error);
}

// Sprawdź, czy żądanie do tego skryptu zostało wysłane metodą POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobierz wartość 'id_produktu' z danych przesłanych metodą POST.
    $id_produktu = $_POST['id_produktu'];
    // Pobierz wartość 'ocena' z danych przesłanych metodą POST.
    $ocena = $_POST['ocena'];
    // Pobierz wartość 'imie' z danych POST i zabezpiecz ją przed atakami XSS za pomocą funkcji htmlspecialchars().
    $imie = htmlspecialchars($_POST['imie']);
    // Pobierz wartość 'opinia' z danych POST i zabezpiecz ją przed atakami XSS za pomocą funkcji htmlspecialchars().
    $opinia = htmlspecialchars($_POST['opinia']);

    // Przygotuj zapytanie SQL do wstawienia nowej opinii do tabeli 'opinie'.
    // Używamy prepared statements (parametryzowanych zapytań) dla bezpieczeństwa.
    $sql_wstaw_opinia = "INSERT INTO opinie (id_produktu, ocena, komentarz, imie) VALUES (?, ?, ?, ?)";
    // Przygotuj zapytanie SQL na utworzonym połączeniu z bazą danych.
    $stmt_wstaw_opinia = $polaczenie->prepare($sql_wstaw_opinia);
    // Powiąż parametry z zapytaniem SQL. "iiss" oznacza typy danych kolejnych parametrów:
    // i - integer (id_produktu, ocena)
    // s - string (komentarz, imie)
    $stmt_wstaw_opinia->bind_param("iiss", $id_produktu, $ocena, $opinia, $imie);

    // Wykonaj przygotowane zapytanie SQL.
    if ($stmt_wstaw_opinia->execute()) {
        // Jeśli zapytanie wykonało się pomyślnie, wyświetl komunikat o sukcesie.
        echo "Opinia dodana pomyślnie.";
    } else {
        // Jeśli wystąpił błąd podczas wykonywania zapytania, wyświetl komunikat o błędzie wraz z informacją o błędzie z bazy danych.
        echo "Błąd podczas dodawania opinii: " . $polaczenie->error;
    }

    // Zamknij przygotowane zapytanie, aby zwolnić zasoby.
    $stmt_wstaw_opinia->close();
} else {
    // Jeśli żądanie nie było metodą POST, wyświetl komunikat o błędzie.
    echo "Błąd: Brak danych.";
}

// Zamknij połączenie z bazą danych, aby zwolnić zasoby.
$polaczenie->close();
?>