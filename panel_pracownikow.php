<?php
session_start();
include 'auth_utils.php';
sprawdz_i_przekieruj(['pracownik', 'admin'], 'index.php', 'Brak uprawnień!');

$polaczenie = new mysqli('localhost', 'root', '', 'buty');
if ($polaczenie->connect_error) {
    die("Błąd połączenia: " . $polaczenie->connect_error);
}

// Pobierz dane pracowników
$sql = "SELECT * FROM pracownicy";
$wynik = $polaczenie->query($sql);

if ($wynik->num_rows > 0) {
    echo "<h2>Panel Pracowników</h2>";
    echo "<table>";
    echo "<thead><tr><th>ID</th><th>Użytkownik</th><th>Email</th><th>Stanowisko</th><th>Data Zatrudnienia</th><th>Akcje</th></tr></thead>";
    echo "<tbody>";
    while ($row = $wynik->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_pracownika"] . "</td>";
        echo "<td>" . htmlspecialchars($row["nazwa_uzytkownika"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["stanowisko"]) . "</td>";
        echo "<td>" . $row["data_zatrudnienia"] . "</td>";
        echo "<td><a href='edytuj_pracownika.php?id=" . $row["id_pracownika"] . "'>Edytuj</a> | <a href='usun_pracownika.php?id=" . $row["id_pracownika"] . "'>Usuń</a></td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} else {
    echo "Brak danych pracowników.";
}

$polaczenie->close();

?>