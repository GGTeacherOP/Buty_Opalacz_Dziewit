<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Pracownika Sklepu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .header {
      background-color: #007bff;
      color: white;
      padding: 1.5rem;
      border-radius: 1rem 1rem 0 0;
    }
    .nav-link {
      font-size: 1.1rem;
    }
     h1 {
      color: #333;
    }

    .back-button {
      background-color: #007bff;
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s ease;
      margin-top: 20px;
    }

    .back-button:hover {
      background-color: darkblue;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <div class="card">
      <div class="header text-center">
        <h2>Panel Pracownika Sklepu</h2>
        <p>Witaj! Wybierz jedną z dostępnych opcji poniżej:</p>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <div class="col-md-6 mb-4">
            <a href="lista_zamowien.php" class="btn btn-outline-primary w-100 py-3">Zamówienia klientów</a>
          </div>
          <div class="col-md-6 mb-4">
            <a href="produkty.php" class="btn btn-outline-success w-100 py-3">Lista produktów</a>
          </div>
         
          <button class="back-button" onclick="window.location.href='index.php'">
    Powrót do strony głównej
  </button>
        </div>
      </div>
    </div>
  </div>


</body>
</html>