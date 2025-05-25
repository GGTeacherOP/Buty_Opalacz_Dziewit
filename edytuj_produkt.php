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
    $id_produktu = $_POST['id_produktu'];
    $nazwa = $_POST['nazwa'];
    $marka = $_POST['marka'];
    $kategoria = $_POST['kategoria'];
    $cena = $_POST['cena'];
    $opis = $_POST['opis'];
    $zdjecie = $_POST['url_zdjecia'];

    $sql = "UPDATE produkty SET 
            nazwa='$nazwa', 
            marka='$marka', 
            kategoria='$kategoria', 
            cena='$cena',
            opis='$opis',
            zdjecie='$zdjecie'
            WHERE id_produktu='$id_produktu'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['komunikat'] = "Produkt został zaktualizowany.";
        header("Location: zarzadzaj_produktami.php");
        exit;
    } else {
        $error = "Błąd aktualizacji produktu: " . $conn->error;
    }
}

// Pobierz dane produktu do formularza
if (isset($_GET['id'])) {
    $id_produktu = $_GET['id'];
    $sql = "SELECT id_produktu, nazwa, marka, kategoria, cena, opis, url_zdjecia FROM produkty WHERE id_produktu='$id_produktu'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $nazwa = $row['nazwa'];
        $marka = $row['marka'];
        $kategoria = $row['kategoria'];
        $cena = $row['cena'];
        $opis = $row['opis'];
        $zdjecie = $row['url_zdjecia'];
    } else {
        $_SESSION['error'] = "Nie znaleziono produktu o podanym ID.";
        header("Location: zarzadzaj_produktami.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Nieprawidłowe zapytanie.";
    header("Location: zarzadzaj_produktami.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora - Edytuj Produkt</title>
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
                <h2>Edytuj Produkt</h2>
                <p>Zaktualizuj dane produktu.</p>
            </div>
            <div class="card-body">

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="post">
                    <input type="hidden" name="id_produktu" value="<?= $id_produktu ?>">
                    <div class="mb-3">
                        <label for="nazwa" class="form-label">Nazwa</label>
                        <input type="text" class="form-control" id="nazwa" name="nazwa" value="<?= $nazwa ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="marka" class="form-label">Marka</label>
                        <input type="text" class="form-control" id="marka" name="marka" value="<?= $marka ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategoria" class="form-label">Kategoria</label>
                        <input type="text" class="form-control" id="kategoria" name="kategoria" value="<?= $kategoria ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="cena" class="form-label">Cena</label>
                        <input type="number" step="0.01" class="form-control" id="cena" name="cena" value="<?= $cena ?>" required>
                    </div>
                     <div class="mb-3">
                        <label for="opis" class="form-label">Opis</label>
                        <textarea class="form-control" id="opis" name="opis" rows="3" required><?= $opis ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="zdjecie" class="form-label">Ścieżka do zdjęcia</label>
                        <input type="text" class="form-control" id="zdjecie" name="zd
                        zdjecie" value="<?= $zdjecie ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Zapisz</button>
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