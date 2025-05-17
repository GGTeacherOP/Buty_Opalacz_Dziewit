<?php
session_start();
include 'auth_utils.php'; // Dołącz plik z funkcją czy_zalogowany()

$host = "localhost";
$uzytkownik_db = "root";
$haslo_db = "";
$nazwa_bazy = "buty";

$polaczenie = new mysqli($host, $uzytkownik_db, $haslo_db, $nazwa_bazy);

if ($polaczenie->connect_error) {
    die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Nie sprawdzamy zalogowania, bo nie uzywamy id_uzytkownika
    $id_produktu = $_POST['id_produktu'];
    $ocena = $_POST['ocena'];
    $imie = htmlspecialchars($_POST['imie']); // Oczyszczanie danych
    $opinia = htmlspecialchars($_POST['opinia']); // Oczyszczanie danych

    // Wstaw nową opinię do bazy danych
    $sql_wstaw_opinia = "INSERT INTO opinie (id_produktu, ocena, komentarz, imie) VALUES (?, ?, ?, ?)";
    $stmt_wstaw_opinia = $polaczenie->prepare($sql_wstaw_opinia);
    $stmt_wstaw_opinia->bind_param("iiss", $id_produktu, $ocena, $opinia, $imie);

    if ($stmt_wstaw_opinia->execute()) {
        echo "Opinia dodana pomyślnie.";
    } else {
        echo "Błąd podczas dodawania opinii: " . $polaczenie->error;
    }

    $stmt_wstaw_opinia->close();
} else {
    echo "Błąd: Brak danych.";
}

$polaczenie->close();
?>