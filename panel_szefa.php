<?php
session_start();
include 'auth_utils.php';
sprawdz_i_przekieruj(['szef'], 'index.php', 'Brak uprawnień Szefa!');

$polaczenie = new mysqli('localhost', 'root', '', 'buty');
if ($polaczenie->connect_error) {
    die("Błąd połączenia: " . $polaczenie->connect_error);
}

echo "<h1>Panel Szefa</h1>";
echo "<p>Witaj, Szefie! Masz pełną kontrolę nad sklepem.</p>";

// Funkcje Szefa
echo "<h2>Zarządzanie Pracownikami</h2>";
echo "<a href='dodaj_pracownika.php'>Dodaj Pracownika</a> | ";
echo "<a href='lista_pracownikow.php'>Lista Pracowników</a><br>";

echo "<h2>Raporty</h2>";
echo "<a href='raport_sprzedazy.php'>Raport Sprzedaży</a> | ";
echo "<a href='raport_magazyn.php'>Raport Magazynu</a><br>";

// Zamknij połączenie
$polaczenie->close();
?>