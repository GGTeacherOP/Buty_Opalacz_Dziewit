<?php
session_start();
include 'auth_utils.php';

if (!czy_ma_role(['kierownik', 'admin', 'szef'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Kierownika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 1rem 1rem 0 0;
            padding: 1rem;
        }
        .card-body {
            padding: 1.5rem;
        }
        .list-group-item {
            border: none;
            padding: 1rem;
            text-align: center;
        }
        .list-group-item:hover {
            background-color: #e9ecef;
        }
        .list-group-item.active {
            background-color: #007bff;
            color: white;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Panel Kierownika</h2>
                <p>Witaj w panelu zarządzania sklepu. Wybierz opcję z listy poniżej.</p>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="zamowienia_kierownika.php" class="list-group-item list-group-item-action">
                        Zarządzaj Zamówieniami
                    </a>
                    <a href="produkty_kierownika.php" class="list-group-item list-group-item-action">
                        Zarządzaj Produktami
                    </a>
                    <a href="pracownicy.php" class="list-group-item list-group-item-action">
                        Zarządzaj Pracownikami
                    </a>
                    <a href="index.php" class="list-group-item list-group-item-action">
                        Wróć na stronę główną
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
