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
    $nazwa_uzytkownika = $_POST['nazwa_uzytkownika'];
    $haslo = $_POST['haslo'];
    $email = $_POST['email'];
    $stanowisko = $_POST['stanowisko'];
    $pensja = $_POST['pensja'];
    $data_zatrudnienia = $_POST['data_zatrudnienia']; // Pobranie daty zatrudnienia

    // Zabezpieczenie przed SQL Injection - UŻYWAJ PREPARED STATEMENTS!
    $sql = "INSERT INTO pracownicy (nazwa_uzytkownika, haslo, email, stanowisko, pensja, data_zatrudnienia)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssds", $nazwa_uzytkownika, $haslo, $email, $stanowisko, $pensja, $data_zatrudnienia);

    if ($stmt->execute()) {
        header("Location: zarzadzaj_pracownikami"); // Przekierowanie po dodaniu
        exit;
    } else {
        $error = "Błąd dodawania pracownika: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Kierownika - Dodaj Pracownika</title>
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
                <h2>Dodaj Pracownika</h2>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label for="nazwa_uzytkownika" class="form-label">Nazwa Użytkownika</label>
                        <input type="text" class="form-control" id="nazwa_uzytkownika" name="nazwa_uzytkownika" required>
                    </div>
                    <div class="mb-3">
                        <label for="haslo" class="form-label">Hasło</label>
                        <input type="password" class="form-control" id="haslo" name="haslo" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="stanowisko" class="form-label">Stanowisko</label>
                        <input type="text" class="form-control" id="stanowisko" name="stanowisko" required>
                    </div>
                    <div class="mb-3">
                        <label for="pensja" class="form-label">Pensja</label>
                        <input type="number" class="form-control" id="pensja" name="pensja" required>
                    </div>
                    <div class="mb-3">
                        <label for="data_zatrudnienia" class="form-label">Data Zatrudnienia</label>
                        <input type="date" class="form-control" id="data_zatrudnienia" name="data_zatrudnienia" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                    <a href="zarzadzaj_pracownikami.php" class="btn btn-secondary">Anuluj</a>
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