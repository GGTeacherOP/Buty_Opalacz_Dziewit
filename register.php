<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rejestracja</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    .auth-container {
      max-width: 400px;
      margin: 4rem auto;
      background: #fff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .auth-container h2 {
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .auth-container input {
      width: 100%;
      padding: 0.7rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }

    .auth-container button {
      width: 100%;
      padding: 0.8rem;
      background-color: #007bff;
      border: none;
      color: white;
      font-size: 1rem;
      border-radius: 6px;
      cursor: pointer;
    }

    .auth-container button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <header>
      <a href="index.html">Strona główna</a>
      <a href="sklep.html">Sklep</a>
      <a href="koszyk.php">Koszyk</a>
      <a href="kontakt.html">Kontakt</a>
      <a href="opinie.html">Opinie</a>
      <a href="aktualnosci.html">Aktualności</a>
       <a href="login.php" class="zg">Zaloguj</a>
      <a href="register.php" class="zg active">Zarejestruj</a>
    </header>

    <div class="auth-container">
      <h2>Załóż konto</h2>
      <form action="register.php" method="POST">
        <input type="text" name="username" placeholder="Nazwa użytkownika" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Hasło" required>
        <button type="submit">Zarejestruj się</button>
      </form>
    </div>
</div>
    <footer>
      <p>&copy; 2025 Sklep z Butami | kontakt@buty.pl</p>
    </footer>
  </div>
</body>
</html>
