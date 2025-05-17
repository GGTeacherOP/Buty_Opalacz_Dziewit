<?php
session_start();
include 'auth_utils.php';
sprawdz_i_przekieruj(['admin', 'szef'], 'index.php', 'Brak uprawnień Admina!');

$polaczenie = new mysqli('localhost', 'root', '', 'buty');
if ($polaczenie->connect_error) {
    die("Błąd połączenia: " . $polaczenie->connect_error);
}

echo "<h1>Panel Admina</h1>";
echo "<p>Witaj, Adminie! Zarządzaj produktami i zamówieniami.</p>";

// Funkcje Admina
echo "<h2>Zarządzanie Produktami</h2>";
echo "<a href='dodaj_produkt.php'>Dodaj Produkt</a> | ";
echo "<a href='lista_produktow.php'>Lista Produktów</a><br>";

echo "<h2>Zarządzanie Zamówieniami</h2>";
echo "<a href='lista_zamowien.php'>Lista Zamówień</a><br>";

$polaczenie->close();
?>