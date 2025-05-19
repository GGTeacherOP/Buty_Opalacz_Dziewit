<?php
// auth_utils.php

/**
 * Sprawdza, czy użytkownik ma jedną z wymaganych ról.
 * Funkcja ta pobiera rolę aktualnie zalogowanego użytkownika z sesji
 * i porównuje ją z listą dozwolonych ról.
 *
 * @param string|array $wymagane_role Rola (string) lub tablica ról (array), które są dozwolone.
 * Jeśli podano string, sprawdzana jest dokładna zgodność.
 * Jeśli podano tablicę, sprawdzane jest, czy rola użytkownika znajduje się w tej tablicy.
 * @return bool True, jeśli użytkownik ma uprawnienia (jego rola pasuje do jednej z wymaganych),
 * false w przeciwnym razie (użytkownik niezalogowany lub jego rola nie pasuje).
 */
function czy_ma_role($wymagane_role) {
    // Sprawdzenie, czy w sesji istnieje zmienna 'rola'. Jeśli nie, użytkownik nie jest zalogowany i nie ma przypisanej roli.
    if (!isset($_SESSION['rola'])) {
        return false; // Użytkownik niezalogowany
    }

    // Sprawdzenie, czy $wymagane_role jest tablicą.
    if (is_array($wymagane_role)) {
        // Jeśli jest tablicą, sprawdzamy, czy rola użytkownika (pobrana z sesji) znajduje się w tej tablicy za pomocą funkcji in_array().
        return in_array($_SESSION['rola'], $wymagane_role);
    } else {
        // Jeśli $wymagane_role nie jest tablicą (czyli jest stringiem), sprawdzamy, czy rola użytkownika dokładnie odpowiada tej wymaganej roli.
        return $_SESSION['rola'] == $wymagane_role;
    }
}

/**
 * Przekierowuje użytkownika i wyświetla komunikat o błędzie, jeśli nie ma uprawnień.
 * Ta funkcja łączy sprawdzenie uprawnień z akcją przekierowania i ustawieniem komunikatu błędu w sesji.
 *
 * @param string|array $wymagane_role Rola (string) lub tablica ról (array), które są dozwolone (tak jak w funkcji czy_ma_role()).
 * @param string $przekierowanie URL, na który użytkownik zostanie przekierowany, jeśli nie ma wymaganych uprawnień.
 * @param string $wiadomosc Komunikat tekstowy, który zostanie zapisany w sesji pod kluczem 'blad_uprawnien' i może być wyświetlony na stronie przekierowania.
 */
function sprawdz_i_przekieruj($wymagane_role, $przekierowanie, $wiadomosc) {
    // Wywołanie funkcji czy_ma_role() w celu sprawdzenia, czy użytkownik posiada wymagane uprawnienia.
    if (!czy_ma_role($wymagane_role)) {
        // Jeśli użytkownik nie ma uprawnień, zapisujemy komunikat o błędzie w zmiennej sesyjnej 'blad_uprawnien'.
        $_SESSION['blad_uprawnien'] = $wiadomosc;
        // Wykonujemy przekierowanie HTTP na podany URL.
        header("Location: " . $przekierowanie);
        // Zakończenie wykonywania bieżącego skryptu po przekierowaniu jest ważne, aby zapobiec dalszemu ładowaniu strony.
        exit;
    }
}

/**
 * Pobiera dane pracownika (np. pensję) z bazy danych na podstawie id_uzytkownika.
 * Funkcja ta wykonuje zapytanie do bazy danych w celu wyszukania informacji o pracowniku powiązanym z podanym ID użytkownika.
 *
 * @param mysqli $polaczenie Obiekt połączenia z bazą danych MySQL. Funkcja wymaga aktywnego połączenia z bazą.
 * @param int $id_uzytkownika Unikalny identyfikator użytkownika, który jest powiązany z danymi pracownika w tabeli 'pracownicy'.
 * @return array|null Tablica asocjacyjna zawierająca dane pracownika (nazwy kolumn jako klucze) lub null, jeśli nie znaleziono pracownika o podanym ID użytkownika.
 */
function pobierz_dane_pracownika($polaczenie, $id_uzytkownika) {
    // Definiowanie zapytania SQL do pobrania wszystkich kolumn z tabeli 'pracownicy' dla wiersza, gdzie 'id_uzytkownika' odpowiada podanej wartości.
    $sql = "SELECT * FROM pracownicy WHERE id_uzytkownika = ?";
    // Przygotowanie zapytania SQL do wykonania. Prepared statements są bezpieczniejsze i wydajniejsze.
    $stmt = $polaczenie->prepare($sql);
    // Sprawdzenie, czy przygotowanie zapytania powiodło się. Jeśli nie, wyświetl błąd i zakończ skrypt.
    if (!$stmt) {
        die("Błąd zapytania: " . $polaczenie->error);
    }
    // Powiązanie parametru (id_uzytkownika) z przygotowanym zapytaniem. "i" oznacza, że parametr jest typu integer.
    $stmt->bind_param("i", $id_uzytkownika);
    // Wykonanie przygotowanego zapytania.
    $stmt->execute();
    // Pobranie wyniku zapytania.
    $result = $stmt->get_result();
    // Sprawdzenie, czy zapytanie zwróciło dokładnie jeden wiersz (oczekiwane dla unikalnego id_uzytkownika).
    if ($result->num_rows === 1) {
        // Jeśli znaleziono jednego pracownika, pobierz jego dane jako tablicę asocjacyjną (klucze to nazwy kolumn).
        return $result->fetch_assoc();
    } else {
        // Jeśli nie znaleziono żadnego pracownika lub znaleziono więcej niż jednego (co nie powinno się zdarzyć przy poprawnym ID), zwróć null.
        return null;
    }
}
?>