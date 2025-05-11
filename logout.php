<?php
session_start();
session_destroy(); // Niszczy sesję użytkownika (wylogowanie)
header("Location: index.php"); // Przekierowanie na stronę główną
exit;