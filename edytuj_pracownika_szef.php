<?php
session_start();
include 'auth_utils.php';

if (!czy_ma_role(['kierownik', 'admin', 'szef'])) {
    header("Location: index.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "buty");
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pracownika = $_POST['id_pracownika'];
    $nazwa_uzytkownika = $_POST['nazwa_uzytkownika'];
    $email = $_POST['email'];
    $stanowisko = $_POST['stanowisko'];
    $pensja = $_POST['pensja'];

    $sql = "UPDATE pracownicy SET 
            nazwa_uzytkownika='$nazwa_uzytkownika', 
            email='$email', 
            stanowisko='$stanowisko', 
            pensja='$pensja' 
            WHERE id_pracownika='$id_pracownika'";

    if ($conn->query($sql) === TRUE) {
        header("Location: pracownicy_szef.php"); // Przekierowanie po edycji
        exit;
    } else {
        $error = "Błąd edycji pracownika: " . $conn->error;
    }
}

// Pobierz dane pracownika do formularza
if (isset($_GET['id'])) {
    $id_pracownika = $_GET['id'];
    $sql = "SELECT id_pracownika, nazwa_uzytkownika, email, stanowisko, pensja FROM pracownicy WHERE id_pracownika='$id_pracownika'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $nazwa_uzytkownika = $row['nazwa_uzytkownika'];
        $email = $row['email'];
        $stanowisko = $row['stanowisko'];
        $pensja = $row['pensja'];
    } else {
        header("Location: pracownicy_szef.php"); // Przekierowanie, jeśli nie znaleziono
        exit;
    }
} else {
    header("Location: pracownicy_szef.php"); // Przekierowanie, jeśli brak ID
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Kierownika - Edytuj Pracownika</title>
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
        .alert-danger {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Edytuj Pracownika</h2>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form method="post">
                    <input type="hidden" name="id_pracownika" value="<?= $id_pracownika ?>">
                    <div class="mb-3">
                        <label for="nazwa_uzytkownika" class="form-label">Nazwa Użytkownika</label>
                        <input type="text" class="form-control" id="nazwa_uzytkownika" name="nazwa_uzytkownika" value="<?= $nazwa_uzytkownika ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="stanowisko" class="form-label">Stanowisko</label>
                        <input type="text" class="form-control" id="stanowisko" name="stanowisko" value="<?= $stanowisko ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="pensja" class="form-label">Pensja</label>
                        <input type="number" class="form-control" id="pensja" name="pensja" value="<?= $pensja ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                    <a href="pracownicy_szef.php" class="btn btn-secondary">Anuluj</a>
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