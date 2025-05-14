<?php
// auth_utils.php

/**
 * Sprawdza, czy użytkownik ma jedną z wymaganych ról.
 *
 * @param string|array $wymagane_role Rola lub tablica ról, które są dozwolone.
 * @return bool True, jeśli użytkownik ma uprawnienia, false w przeciwnym razie.
 */
function czy_ma_role($wymagane_role) {
    if (!isset($_SESSION['rola'])) {
        return false; // Użytkownik niezalogowany
    }

    if (is_array($wymagane_role)) {
        return in_array($_SESSION['rola'], $wymagane_role);
    } else {
        return $_SESSION['rola'] == $wymagane_role;
    }
}

/**
 * Przekierowuje użytkownika i wyświetla komunikat o błędzie, jeśli nie ma uprawnień.
 *
 * @param string|array $wymagane_role Rola lub tablica ról, które są dozwolone.
 * @param string $przekierowanie URL do przekierowania po braku uprawnień.
 * @param string $wiadomosc Komunikat do wyświetlenia.
 */
function sprawdz_i_przekieruj($wymagane_role, $przekierowanie, $wiadomosc) {
    if (!czy_ma_role($wymagane_role)) {
        $_SESSION['blad_uprawnien'] = $wiadomosc;
        header("Location: " . $przekierowanie);
        exit;
    }
}

/**
 * Pobiera dane pracownika (np. pensję) na podstawie id_uzytkownika.
 *
 * @param mysqli $polaczenie Połączenie z bazą danych.
 * @param int $id_uzytkownika ID użytkownika.
 * @return array|null Tablica z danymi pracownika lub null, jeśli nie znaleziono.
 */
function pobierz_dane_pracownika($polaczenie, $id_uzytkownika) {
    $sql = "SELECT * FROM pracownicy WHERE id_uzytkownika = ?";
    $stmt = $polaczenie->prepare($sql);
    if (!$stmt) {
        die("Błąd zapytania: " . $polaczenie->error);
    }
    $stmt->bind_param("i", $id_uzytkownika);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}
?>