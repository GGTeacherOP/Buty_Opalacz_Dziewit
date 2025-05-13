-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Maj 13, 2025 at 08:57 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `buty`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `elementy_zamowienia`
--

CREATE TABLE `elementy_zamowienia` (
  `id_elementu_zamowienia` int(11) NOT NULL,
  `id_zamowienia` int(11) DEFAULT NULL,
  `id_produktu` int(11) DEFAULT NULL,
  `ilosc` int(11) NOT NULL,
  `cena_jednostkowa` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `elementy_zamowienia`
--

INSERT INTO `elementy_zamowienia` (`id_elementu_zamowienia`, `id_zamowienia`, `id_produktu`, `ilosc`, `cena_jednostkowa`) VALUES
(1, 2, 1, 1, 499.00),
(2, 3, 1, 1, 499.00),
(3, 4, 12, 1, 529.00),
(4, 5, 1, 1, 499.00),
(5, 6, 20, 1, 179.00),
(6, 7, 1, 1, 499.00),
(7, 8, 15, 1, 379.00),
(8, 9, 2, 1, 1249.00),
(9, 10, 1, 1, 499.00),
(10, 11, 2, 1, 1249.00),
(11, 12, 1, 1, 499.00),
(12, 14, 1, 1, 499.00),
(13, 14, 1, 1, 499.00),
(14, 15, 1, 1, 499.00),
(15, 15, 1, 1, 499.00),
(16, 16, 1, 1, 499.00),
(17, 17, 1, 1, 499.00),
(18, 18, 1, 1, 499.00),
(19, 19, 1, 1, 499.00),
(20, 20, 1, 2, 499.00),
(21, 21, 1, 4, 499.00),
(22, 22, 1, 1, 499.00);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `opinie`
--

CREATE TABLE `opinie` (
  `id_opinii` int(11) NOT NULL,
  `id_produktu` int(11) DEFAULT NULL,
  `id_uzytkownika` int(11) DEFAULT NULL,
  `ocena` int(11) DEFAULT NULL,
  `komentarz` text DEFAULT NULL,
  `data_opinii` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `produkty`
--

CREATE TABLE `produkty` (
  `id_produktu` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `opis` text DEFAULT NULL,
  `cena` decimal(10,2) NOT NULL,
  `marka` varchar(100) DEFAULT NULL,
  `kategoria` varchar(100) DEFAULT NULL,
  `url_zdjecia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produkty`
--

INSERT INTO `produkty` (`id_produktu`, `nazwa`, `opis`, `cena`, `marka`, `kategoria`, `url_zdjecia`) VALUES
(1, 'Nike Air Force 1 Białe', 'Klasyczne białe sneakersy Nike Air Force 1.', 499.00, 'Nike', 'Sneakersy', 'img/Nike/AF1/AF1white.jpg'),
(2, 'Jordan 1 Mocha', 'Stylowe Jordan 1 Mocha.', 1249.00, 'Jordan', 'Sneakersy', 'img/Jordan/Mocha/Mocha1.jpeg'),
(3, 'Adidas Campus 00s Beżowe', 'Wygodne Adidas Campus 00s w kolorze beżowym.', 529.00, 'Adidas', 'Sneakersy', 'img/Adidas/Campus/1.avif'),
(4, 'Adidas Campus 00s Czarne', 'Klasyczne czarne Adidas Campus 00s.', 529.00, 'Adidas', 'Sneakersy', 'img/Adidas/Campus/campus1.jpg'),
(5, 'Adidas Samba OG', 'Oryginalne Adidas Samba OG.', 429.00, 'Adidas', 'Sneakersy', 'img/Adidas/Samba/samba1.jpg'),
(6, 'Jordan 4 Military Black', 'Eleganckie Jordan 4 Military Black.', 1399.00, 'Jordan', 'Sneakersy', 'img/Jordan/Military/Military1.jpg'),
(7, 'Nike Pegasus Premium', 'Wysokiej jakości buty do biegania Nike Pegasus Premium.', 899.00, 'Nike', 'Biegania', 'img/Nike/Nike Pegasus Premium/pegasusprem1.png'),
(8, 'Nike Air Max 90', 'Ikoniczne Nike Air Max 90.', 649.00, 'Nike', 'Sneakersy', 'img/Nike/AIR MAX/MAX1.png'),
(9, 'Reebook FIORI', 'Buty do biegania Reebook FIORI.', 250.00, 'Reebok', 'Biegania', 'img/Reebook/Reebok FIORI/Rebook FIORI1.jpg'),
(10, 'Converse Chuck Taylor All Star', 'Ponadczasowe Converse Chuck Taylor All Star.', 349.00, 'Converse', 'Trampki', 'img/Converse/ConverseAllStar/ConverseALLStar1.jpg'),
(11, 'Converse All Star Platform Czarny', 'Modne Converse All Star Platform w kolorze czarnym.', 399.00, 'Converse', 'Trampki', 'img/Converse/ConversePlatform/ConversePlatform1 (1).jpg'),
(12, 'Adidas Forum Low', 'Stylowe Adidas Forum Low.', 499.00, 'Adidas', 'Sneakersy', 'img/Adidas/ForumBlack/Forum1.jpg'),
(13, 'Jordan 1 Chicago', 'Legendarne Jordan 1 Chicago.', 1599.00, 'Jordan', 'Sneakersy', 'img/Jordan/J1Chicago/J1Chicago1.jpg'),
(14, 'Adidas COPA PURE 2', 'Buty treningowe Adidas COPA PURE 2.', 350.00, 'Adidas', 'Treningowe', 'img/Adidas/COPA PURE 2 CLUB IN/add1.jpg'),
(15, 'Vans Old Skool Czarny', 'Klasyczne Vans Old Skool w kolorze czarnym.', 379.00, 'Vans', 'Trampki', 'img/VANS/VansOld/VansOld1.avif'),
(16, 'Vans Sk8-Hi Biało-Czarne', 'Stylowe Vans Sk8-Hi w kolorach biało-czarnych.', 429.00, 'Vans', 'Trampki', 'img/VANS/VansSk8/VansSk81.avif'),
(17, 'Nike Klapki Białe', 'Wygodne klapki Nike w kolorze białym.', 199.00, 'Nike', 'Klapki', 'img/Nike/KlapkiBiale/1.avif'),
(18, 'Nike Klapki Czarne', 'Wygodne klapki Nike w kolorze czarnym.', 199.00, 'Nike', 'Klapki', 'img/Nike/KlpakiCzarne/1.avif'),
(19, 'Under Armour Infinite', 'Zaawansowane buty do biegania Under Armour Infinite.', 699.00, 'Under Armour', 'biegania', 'img/UnderArmour/Infinite/UA_W_Infinite_Elite_2_1.png'),
(20, 'Adidas Klapki Białe', 'Wygodne klapki Adidas w kolorze białym.', 179.00, 'Adidas', 'Klapki', 'img/Adidas/KlapkiBiale/1.avif'),
(21, 'Adidas Klapki Czarne', 'Wygodne klapki Adidas w kolorze czarnym.', 179.00, 'Adidas', 'Klapki', 'img/Adidas/KlapkiCzarne/1.avif'),
(22, 'Jordan Klapki Czarne', 'Stylowe klapki Jordan w kolorze czarnym.', 250.00, 'Jordan', 'Klapki', 'img/Jordan/KlapkiCzarne/1.avif'),
(23, 'Under Armour Magnetico', 'Buty treningowe Under Armour Magnetico.', 299.00, 'Under Armour', 'Treningowe', 'img/UnderArmour/Magnetico/UA_Magnetico_Elite_4Fg_1.png'),
(24, 'Jordan Klapki Białe', 'Stylowe klapki Jordan w kolorze białym.', 250.00, 'Jordan', 'Klapki', 'img/Jordan/KlapkiBiale/1.avif');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id_uzytkownika` int(11) NOT NULL,
  `nazwa_uzytkownika` varchar(50) NOT NULL,
  `haslo` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rola` enum('klient','pracownik') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id_uzytkownika`, `nazwa_uzytkownika`, `haslo`, `email`, `rola`) VALUES
(1, 'jan_kowalski', 'haslo123', 'jan.kowalski@example.com', 'klient'),
(2, 'maria_nowak', 'tajnehaslo', 'maria.nowak@example.com', 'klient'),
(3, 'admin123', 'adminpass', 'admin123@example.com', 'pracownik'),
(4, 'anna_smith', 'password456', 'anna.smith@example.com', 'klient'),
(5, 'piotr_wisniewski', 'sekretne123', 'piotr.wisniewski@example.com', 'klient'),
(6, 'ewa_lewandowska', 'hasloewa', 'ewa.lewandowska@example.com', 'klient'),
(7, 'tomasz_muller', 'tomasz123', 'tomasz.muller@example.com', 'klient'),
(8, 'magdalena_bak', 'magdabak', 'magdalena.bak@example.com', 'klient'),
(9, 'lukasz_nowakowski', 'lukaszpass', 'lukasz.nowakowski@example.com', 'klient'),
(10, 'katarzyna_zielinska', 'katarzynaz', 'katarzyna.zielinska@example.com', 'klient'),
(11, 'michal_wozniak', 'michal123', 'michal.wozniak@example.com', 'klient'),
(12, 'aleksandra_duda', 'aleksandrad', 'aleksandra.duda@example.com', 'klient'),
(13, 'marcin_kaminski', 'marcinkam', 'marcin.kaminski@example.com', 'klient'),
(14, 'natalia_sokolowska', 'natalias', 'natalia.sokolowska@example.com', 'klient'),
(15, 'dawid_gorski', 'dawidgorski', 'dawid.gorski@example.com', 'klient'),
(16, 'karolina_mazur', 'karolinam', 'karolina.mazur@example.com', 'klient'),
(17, 'sebastian_kowalczyk', 'sebastiank', 'sebastian.kowalczyk@example.com', 'klient'),
(18, 'wiktoria_rutkowska', 'wiktoriar', 'wiktoria.rutkowska@example.com', 'klient'),
(19, 'filip_zawadzki', 'filipz', 'filip.zawadzki@example.com', 'klient'),
(20, 'julia_kaczmarek', 'juliak', 'julia.kaczmarek@example.com', 'klient'),
(21, 'patryk_jakubowski', 'patrykj', 'patryk.jakubowski@example.com', 'pracownik'),
(22, 'amanda_jablonska', 'amandaj', 'amanda.jablonska@example.com', 'pracownik'),
(23, 'adam_piekarski', 'adamp', 'adam.piekarski@example.com', 'pracownik'),
(24, 'beata_mazurek', 'beatam', 'beata.mazurek@example.com', 'pracownik'),
(25, 'cezary_baranski', 'cezaryb', 'cezary.baranski@example.com', 'pracownik'),
(26, 'diana_sikora', 'dianas', 'diana.sikora@example.com', 'pracownik'),
(27, 'eryk_urban', 'eryku', 'eryk.urban@example.com', 'pracownik'),
(28, 'MarekJ', 'haslo123', 'MarekJ@gmail.com', 'klient'),
(29, 'Andrzej', 'haslo123', 'Andrzej@gmail.com', 'klient'),
(30, 'Bombel', 'haslo123', 'Bombel@gmail.com', 'klient'),
(31, 'Dominik', 'Domino123', 'Dominik@wp.pl', 'klient');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wiadomosci`
--

CREATE TABLE `wiadomosci` (
  `id` int(11) NOT NULL,
  `imie` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pytanie` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wiadomosci`
--

INSERT INTO `wiadomosci` (`id`, `imie`, `email`, `pytanie`) VALUES
(1, 'Andrzej', 'Andrzej@gmail.com', 'Andrzej siemka');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zamowienia`
--

CREATE TABLE `zamowienia` (
  `id_zamowienia` int(11) NOT NULL,
  `id_uzytkownika` int(11) DEFAULT NULL,
  `data_zamowienia` timestamp NOT NULL DEFAULT current_timestamp(),
  `kwota_calkowita` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zamowienia`
--

INSERT INTO `zamowienia` (`id_zamowienia`, `id_uzytkownika`, `data_zamowienia`, `kwota_calkowita`) VALUES
(2, 5, '2025-05-11 16:49:46', 499.00),
(3, 5, '2025-05-11 16:52:12', 499.00),
(4, 7, '2025-05-11 17:00:02', 529.00),
(5, 7, '2025-05-11 17:03:26', 499.00),
(6, 7, '2025-05-11 17:09:57', 179.00),
(7, 7, '2025-05-11 17:10:21', 499.00),
(8, 17, '2025-05-11 17:36:11', 379.00),
(9, 17, '2025-05-11 17:37:00', 1249.00),
(10, 30, '2025-05-11 17:56:26', 499.00),
(11, 30, '2025-05-11 17:58:18', 1249.00),
(12, 31, '2025-05-11 19:29:39', 499.00),
(14, 29, '2025-05-13 17:57:10', 998.00),
(15, 29, '2025-05-13 18:09:33', 998.00),
(16, 29, '2025-05-13 18:17:46', 499.00),
(17, 29, '2025-05-13 18:18:23', 499.00),
(18, 29, '2025-05-13 18:19:24', 499.00),
(19, 29, '2025-05-13 18:21:56', 499.00),
(20, 29, '2025-05-13 18:29:30', 998.00),
(21, 29, '2025-05-13 18:49:53', 1996.00),
(22, 29, '2025-05-13 18:55:16', 499.00);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `elementy_zamowienia`
--
ALTER TABLE `elementy_zamowienia`
  ADD PRIMARY KEY (`id_elementu_zamowienia`),
  ADD KEY `id_zamowienia` (`id_zamowienia`),
  ADD KEY `id_produktu` (`id_produktu`);

--
-- Indeksy dla tabeli `opinie`
--
ALTER TABLE `opinie`
  ADD PRIMARY KEY (`id_opinii`),
  ADD KEY `id_produktu` (`id_produktu`),
  ADD KEY `id_uzytkownika` (`id_uzytkownika`);

--
-- Indeksy dla tabeli `produkty`
--
ALTER TABLE `produkty`
  ADD PRIMARY KEY (`id_produktu`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id_uzytkownika`),
  ADD UNIQUE KEY `nazwa_uzytkownika` (`nazwa_uzytkownika`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeksy dla tabeli `wiadomosci`
--
ALTER TABLE `wiadomosci`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `zamowienia`
--
ALTER TABLE `zamowienia`
  ADD PRIMARY KEY (`id_zamowienia`),
  ADD KEY `id_uzytkownika` (`id_uzytkownika`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `elementy_zamowienia`
--
ALTER TABLE `elementy_zamowienia`
  MODIFY `id_elementu_zamowienia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `opinie`
--
ALTER TABLE `opinie`
  MODIFY `id_opinii` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produkty`
--
ALTER TABLE `produkty`
  MODIFY `id_produktu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id_uzytkownika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `wiadomosci`
--
ALTER TABLE `wiadomosci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `zamowienia`
--
ALTER TABLE `zamowienia`
  MODIFY `id_zamowienia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `elementy_zamowienia`
--
ALTER TABLE `elementy_zamowienia`
  ADD CONSTRAINT `elementy_zamowienia_ibfk_1` FOREIGN KEY (`id_zamowienia`) REFERENCES `zamowienia` (`id_zamowienia`),
  ADD CONSTRAINT `elementy_zamowienia_ibfk_2` FOREIGN KEY (`id_produktu`) REFERENCES `produkty` (`id_produktu`);

--
-- Constraints for table `opinie`
--
ALTER TABLE `opinie`
  ADD CONSTRAINT `opinie_ibfk_1` FOREIGN KEY (`id_produktu`) REFERENCES `produkty` (`id_produktu`),
  ADD CONSTRAINT `opinie_ibfk_2` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id_uzytkownika`);

--
-- Constraints for table `zamowienia`
--
ALTER TABLE `zamowienia`
  ADD CONSTRAINT `zamowienia_ibfk_1` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id_uzytkownika`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
