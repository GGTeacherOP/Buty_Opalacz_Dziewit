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

if (isset($_GET['id'])) {
    $id_klienta_do_usuniecia = $_GET['id'];

    $sql_delete = "DELETE FROM klienci WHERE id_klienta = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id_klienta_do_usuniecia);

    if ($stmt_delete->execute()) {
        $_SESSION['komunikat'] = "Użytkownik został usunięty.";
    } else {
        $_SESSION['error'] = "Błąd podczas usuwania użytkownika: " . $stmt_delete->error;
    }
    $stmt_delete->close();
} else {
    $_SESSION['error'] = "Nieprawidłowe zapytanie.";
}

$conn->close();
header("Location: klienci.php");
exit;
?>