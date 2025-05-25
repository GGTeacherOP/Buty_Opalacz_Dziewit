<?php
session_start(); // Rozpoczęcie sesji PHP, aby uzyskać dostęp do istniejących danych sesji.

// Komentarz wyjaśniający, dlaczego koszyk nie jest usuwany z sesji.
// W tym skrypcie zakłada się, że dane koszyka są przechowywane w bazie danych
// i są powiązane z ID użytkownika. Dlatego nie ma potrzeby usuwania ich z sesji.
// Zamiast tego, celem jest wylogowanie użytkownika, co osiąga się przez zniszczenie sesji.

session_destroy(); // Funkcja niszcząca bieżącą sesję. Usuwa wszystkie dane powiązane z tą sesją,
                 // w tym zmienne sesyjne takie jak 'username', 'rola', 'id_uzytkownika' itp.
                 // Koszyk w bazie danych pozostaje nienaruszony.

header("Location: index.php"); // Przekierowanie przeglądarki użytkownika na stronę główną (index.php).
exit; // Zatrzymanie wykonywania dalszego kodu PHP po przekierowaniu.
      // Jest to dobra praktyka, aby upewnić się, że żadne niechciane operacje nie zostaną wykonane.
?>