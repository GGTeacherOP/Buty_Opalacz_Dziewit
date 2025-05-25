<?php
session_start();
include 'auth_utils.php';

if (!czy_ma_role(['admin', 'szef'])) {
    header("Location: index.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "buty");
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_produktu'])) {
    $id_produktu = $_POST['id_produktu'];

    $sql = "DELETE FROM produkty WHERE id_produktu = $id_produktu";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['komunikat'] = "Produkt został usunięty.";
    } else {
        $_SESSION['error'] = "Błąd usuwania produktu: " . $conn->error;
    }
}

header("Location: produkty_szef.php"); // Powrót do listy produktów
exit;

$conn->close();
?>