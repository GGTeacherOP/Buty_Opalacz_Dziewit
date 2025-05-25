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

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nazwa = $_POST['nazwa'];
    $marka = $_POST['marka'];
    $kategoria = $_POST['kategoria'];
    $cena = $_POST['cena'];
    $opis = $_POST['opis'];  // Dodane pole
    $zdjecie = $_POST['zdjecie']; // Dodane pole

    $sql = "INSERT INTO produkty (nazwa, marka, kategoria, cena, opis, zdjecie) 
            VALUES ('$nazwa', '$marka', '$kategoria', '$cena', '$opis', '$zdjecie')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['komunikat'] = "Produkt został dodany.";
        header("Location: zarzadzaj_produktami.php");
        exit;
    } else {
        $error = "Błąd dodawania produktu: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora - Dodaj Produkt</title>
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
        .form-label {
            font-weight: bold;
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
                <h2>Dodaj Produkt</h2>
                <p>Wprowadź dane nowego produktu.</p>
            </div>
            <div class="card-body">

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label for="nazwa" class="form-label">Nazwa</label>
                        <input type="text" class="form-control" id="nazwa" name="nazwa" required>
                    </div>
                    <div class="mb-3">
                        <label for="marka" class="form-label">Marka</label>
                        <input type="text" class="form-control" id="marka" name="marka" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategoria" class="form-label">Kategoria</label>
                        <input type="text" class="form-control" id="kategoria" name="kategoria" required>
                    </div>
                    <div class="mb-3">
                        <label for="cena" class="form-label">Cena</label>
                        <input type="number" step="0.01" class="form-control" id="cena" name="cena" required>
                    </div>
                     <div class="mb-3">
                        <label for="opis" class="form-label">Opis</label>
                        <textarea class="form-control" id="opis" name="opis" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="zdjecie" class="form-label">Ścieżka do zdjęcia</label>
                        <input type="text" class="form-control" id="zdjecie" name="zdjecie" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                    <a href="zarzadzaj_produktami.php" class="btn btn-secondary">Anuluj</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>