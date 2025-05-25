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
$komunikat = "";

// Pobranie ID użytkownika z GET
if (isset($_GET['id'])) {
    $id_klienta_do_edycji = $_GET['id'];

    // Pobranie danych użytkownika do formularza
    $sql_select = "SELECT id_klienta, nazwa_uzytkownika, email, rola FROM klienci WHERE id_klienta = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $id_klienta_do_edycji);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();

    if ($result_select->num_rows == 1) {
        $uzytkownik = $result_select->fetch_assoc();
        $nazwa_uzytkownika = $uzytkownik['nazwa_uzytkownika'];
        $email = $uzytkownik['email'];
        $rola = $uzytkownik['rola'];
    } else {
        $_SESSION['error'] = "Nie znaleziono użytkownika o podanym ID.";
        header("Location: zarzadzaj_uzytkownikami.php");
        exit;
    }
    $stmt_select->close();
} else {
    $_SESSION['error'] = "Nieprawidłowe zapytanie.";
    header("Location: zarzadzaj_klientami.php");
    exit;
}

// Obsługa formularza po wysłaniu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_klienta = $_POST['id_klienta'];
    $nazwa_uzytkownika_nowa = $_POST['nazwa_uzytkownika'];
    $email_nowy = $_POST['email'];
    $rola_nowa = $_POST['rola'];

    $sql_update = "UPDATE klienci SET nazwa_uzytkownika = ?, email = ?, rola = ? WHERE id_klienta = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssi", $nazwa_uzytkownika_nowa, $email_nowy, $rola_nowa, $id_klienta);

    if ($stmt_update->execute()) {
        $_SESSION['komunikat'] = "Dane użytkownika zostały zaktualizowane.";
        header("Location: zarzadzaj_uzytkownikami.php");
        exit;
    } else {
        $error = "Błąd aktualizacji danych użytkownika: " . $stmt_update->error;
    }
    $stmt_update->close();
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora - Edytuj Użytkownika</title>
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
                <h2>Edytuj Użytkownika</h2>
                <p>Zmień dane użytkownika.</p>
            </div>
            <div class="card-body">

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="post">
                    <input type="hidden" name="id_klienta" value="<?= $id_klienta_do_edycji ?>">
                    <div class="mb-3">
                        <label for="nazwa_uzytkownika" class="form-label">Nazwa Użytkownika</label>
                        <input type="text" class="form-control" id="nazwa_uzytkownika" name="nazwa_uzytkownika" value="<?= $nazwa_uzytkownika ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="rola" class="form-label">Rola</label>
                        <select class="form-control" id="rola" name="rola" required>
                            <option value="klient" <?= ($rola == 'klient') ? 'selected' : '' ?>>Klient</option>
                            <option value="pracownik" <?= ($rola == 'pracownik') ? 'selected' : '' ?>>Pracownik</option>
                            <option value="kierownik" <?= ($rola == 'kierownik') ? 'selected' : '' ?>>Kierownik</option>
                            <option value="admin" <?= ($rola == 'admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="szef" <?= ($rola == 'szef') ? 'selected' : '' ?>>Szef</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Zapisz Zmiany</button>
                    <a href="zarzadzaj_uzytkownikami.php" class="btn btn-secondary">Anuluj</a>
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