<?php
session_start();
include 'auth_utils.php';
sprawdz_i_przekieruj(['kierownik', 'admin', 'szef'], 'index.php', 'Brak uprawnień Kierownika!');

$polaczenie = new mysqli('localhost', 'root', '', 'buty');
if ($polaczenie->connect_error) {
    die("Błąd połączenia: " . $polaczenie->connect_error);
}

echo "<h1>Panel Kierownika</h1>";
echo "<p>Witaj, Kierowniku! Nadzoruj sklep i pracowników.</p>";

// Funkcje Kierownika
echo "<h2>Zamówienia Sklepu</h2>";
echo "<a href='zamowienia_sklep.php'>Pokaż Zamówienia Sklepu</a><br>";

echo "<h2>Magazyn</h2>";
echo "<a href='magazyn_sklep.php'>Stan Magazynu Sklepu</a><br>";

echo "<h2>Pracownicy Sklepu</h2>";
echo "<a href='grafik_pracownikow.php'>Grafik Pracowników</a><br>";

$polaczenie->close();
?>