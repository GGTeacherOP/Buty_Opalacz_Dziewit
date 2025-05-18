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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_pracownika'])) {
    $id_pracownika = $_POST['id_pracownika'];

    $sql = "DELETE FROM pracownicy WHERE id_pracownika = $id_pracownika";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['komunikat'] = "Pracownik został usunięty.";
    } else {
        $_SESSION['error'] = "Błąd usuwania pracownika: " . $conn->error;
    }
}

header("Location: pracownicy_szef.php"); // Powrót do listy pracowników
exit;

$conn->close();
?>