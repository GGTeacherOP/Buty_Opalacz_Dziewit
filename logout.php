<?php
session_start();

// Nie usuwaj koszyka z sesji, bo jest przechowywany w bazie danych
// Po prostu zniszcz sesję
session_destroy();

header("Location: index.php");
exit;
?>