<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Pracownika Sklepu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Jasnoszare tło strony */
        }
        .card {
            border-radius: 1rem; /* Zaokrąglone rogi karty */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Delikatny cień karty dla efektu głębi */
        }
        .header {
            background-color: #007bff; /* Niebieskie tło nagłówka */
            color: white; /* Biały tekst w nagłówku */
            padding: 1.5rem; /* Wewnętrzny odstęp w nagłówku */
            border-radius: 1rem 1rem 0 0; /* Zaokrąglenie tylko górnych rogów nagłówka */
        }
        .nav-link {
            font-size: 1.1rem; /* Większa czcionka dla linków nawigacyjnych (jeśli byłyby użyte) */
        }
        h1 {
            color: #333; /* Ciemnoszary kolor nagłówków */
        }

        .back-button {
            background-color: #007bff; /* Niebieskie tło przycisku powrotu */
            color: white; /* Biały tekst przycisku powrotu */
            padding: 12px 24px; /* Większy wewnętrzny odstęp dla lepszej widoczności */
            border: none; /* Usunięcie domyślnego obramowania przycisku */
            border-radius: 8px; /* Zaokrąglone rogi przycisku */
            cursor: pointer; /* Kursor zmienia się na wskazujący, sygnalizując interaktywność */
            font-size: 16px; /* Większa czcionka tekstu przycisku */
            transition: background-color 0.3s ease; /* Płynne przejście koloru tła przy najechaniu */
            margin-top: 20px; /* Górny margines przycisku, oddzielający go od innych elementów */
        }

        .back-button:hover {
            background-color: darkblue; /* Ciemniejszy niebieski kolor tła przy najechaniu */
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