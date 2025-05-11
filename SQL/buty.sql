-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Maj 10, 2025 at 03:39 PM
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
(29, 'Andrzej', 'haslo123', 'Andrzej@gmail.com', 'klient');

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
  MODIFY `id_elementu_zamowienia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opinie`
--
ALTER TABLE `opinie`
  MODIFY `id_opinii` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produkty`
--
ALTER TABLE `produkty`
  MODIFY `id_produktu` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id_uzytkownika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `zamowienia`
--
ALTER TABLE `zamowienia`
  MODIFY `id_zamowienia` int(11) NOT NULL AUTO_INCREMENT;

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
