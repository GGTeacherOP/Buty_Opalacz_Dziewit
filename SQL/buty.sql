-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Maj 17, 2025 at 05:57 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

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
  `cena_jednostkowa` decimal(10,2) NOT NULL,
  `id_klienta` int(11) DEFAULT NULL,
  `rozmiar` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `elementy_zamowienia`
--

INSERT INTO `elementy_zamowienia` (`id_elementu_zamowienia`, `id_zamowienia`, `id_produktu`, `ilosc`, `cena_jednostkowa`, `id_klienta`, `rozmiar`) VALUES
(12, 13, 1, 2, 499.00, NULL, NULL),
(13, 14, 1, 3, 499.00, NULL, NULL),
(14, 15, 1, 2, 499.00, NULL, NULL),
(15, 16, 1, 4, 499.00, NULL, NULL),
(16, 17, 1, 2, 499.00, 29, '41'),
(17, 18, 1, 3, 499.00, 29, '41'),
(18, 19, 1, 2, 499.00, 29, '40'),
(19, 19, 1, 2, 499.00, 29, '43'),
(20, 20, 1, 2, 499.00, 29, '41'),
(21, 21, 1, 2, 499.00, 29, '40'),
(22, 21, 1, 3, 499.00, 29, '41'),
(23, 22, 1, 3, 499.00, 29, '41');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `klienci`
--

CREATE TABLE `klienci` (
  `id_klienta` int(11) NOT NULL,
  `nazwa_uzytkownika` varchar(50) NOT NULL,
  `haslo` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `data_rejestracji` timestamp NOT NULL DEFAULT current_timestamp(),
  `rola` varchar(20) NOT NULL DEFAULT 'klient'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `klienci`
--

INSERT INTO `klienci` (`id_klienta`, `nazwa_uzytkownika`, `haslo`, `email`, `data_rejestracji`, `rola`) VALUES
(1, 'jan_kowalski', 'haslo123', 'jan.kowalski@example.com', '2025-05-11 19:31:00', 'klient'),
(2, 'maria_nowak', 'tajnehaslo', 'maria.nowak@example.com', '2025-05-11 19:31:00', 'klient'),
(4, 'anna_smith', 'password456', 'anna.smith@example.com', '2025-05-11 19:31:00', 'klient'),
(5, 'piotr_wisniewski', 'sekretne123', 'piotr.wisniewski@example.com', '2025-05-11 19:31:00', 'klient'),
(6, 'ewa_lewandowska', 'hasloewa', 'ewa.lewandowska@example.com', '2025-05-11 19:31:00', 'klient'),
(7, 'tomasz_muller', 'tomasz123', 'tomasz.muller@example.com', '2025-05-11 19:31:00', 'klient'),
(8, 'magdalena_bak', 'magdabak', 'magdalena.bak@example.com', '2025-05-11 19:31:00', 'klient'),
(9, 'lukasz_nowakowski', 'lukaszpass', 'lukasz.nowakowski@example.com', '2025-05-11 19:31:00', 'klient'),
(10, 'katarzyna_zielinska', 'katarzynaz', 'katarzyna.zielinska@example.com', '2025-05-11 19:31:00', 'klient'),
(11, 'michal_wozniak', 'michal123', 'michal.wozniak@example.com', '2025-05-11 19:31:00', 'klient'),
(12, 'aleksandra_duda', 'aleksandrad', 'aleksandra.duda@example.com', '2025-05-11 19:31:00', 'klient'),
(13, 'marcin_kaminski', 'marcinkam', 'marcin.kaminski@example.com', '2025-05-11 19:31:00', 'klient'),
(14, 'natalia_sokolowska', 'natalias', 'natalia.sokolowska@example.com', '2025-05-11 19:31:00', 'klient'),
(15, 'dawid_gorski', 'dawidgorski', 'dawid.gorski@example.com', '2025-05-11 19:31:00', 'klient'),
(16, 'karolina_mazur', 'karolinam', 'karolina.mazur@example.com', '2025-05-11 19:31:00', 'klient'),
(17, 'sebastian_kowalczyk', 'sebastiank', 'sebastian.kowalczyk@example.com', '2025-05-11 19:31:00', 'klient'),
(18, 'wiktoria_rutkowska', 'wiktoriar', 'wiktoria.rutkowska@example.com', '2025-05-11 19:31:00', 'klient'),
(19, 'filip_zawadzki', 'filipz', 'filip.zawadzki@example.com', '2025-05-11 19:31:00', 'klient'),
(20, 'julia_kaczmarek', 'juliak', 'julia.kaczmarek@example.com', '2025-05-11 19:31:00', 'klient'),
(28, 'MarekJ', 'haslo123', 'MarekJ@gmail.com', '2025-05-11 19:31:00', 'klient'),
(29, 'Andrzej', 'haslo123', 'Andrzej@gmail.com', '2025-05-11 19:31:00', 'klient'),
(30, 'Bombel', 'haslo123', 'Bombel@gmail.com', '2025-05-11 19:31:00', 'klient'),
(31, 'Dominik', 'Domino123', 'Dominik@wp.pl', '2025-05-11 19:31:00', 'klient');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `koszyki`
--

CREATE TABLE `koszyki` (
  `id_koszyka` int(11) NOT NULL,
  `id_klienta` int(11) DEFAULT NULL,
  `id_produktu` int(11) DEFAULT NULL,
  `rozmiar` varchar(50) DEFAULT NULL,
  `ilosc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `koszyki`
--

INSERT INTO `koszyki` (`id_koszyka`, `id_klienta`, `id_produktu`, `rozmiar`, `ilosc`) VALUES
(4, 29, 1, '41', 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `opinie`
--

CREATE TABLE `opinie` (
  `id_opinii` int(11) NOT NULL,
  `id_produktu` int(11) DEFAULT NULL,
  `id_klienta` int(11) DEFAULT NULL,
  `ocena` int(11) DEFAULT NULL,
  `komentarz` text DEFAULT NULL,
  `data_opinii` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pracownicy`
--

CREATE TABLE `pracownicy` (
  `id_pracownika` int(11) NOT NULL,
  `nazwa_uzytkownika` varchar(50) NOT NULL,
  `haslo` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `data_zatrudnienia` date DEFAULT NULL,
  `stanowisko` varchar(50) DEFAULT NULL,
  `pensja` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pracownicy`
--

INSERT INTO `pracownicy` (`id_pracownika`, `nazwa_uzytkownika`, `haslo`, `email`, `data_zatrudnienia`, `stanowisko`, `pensja`) VALUES
(1, 'admin123', 'adminpass', 'admin123@example.com', '2025-05-11', 'Pracownik sklepu', 4900.00),
(2, 'patryk_jakubowski', 'patrykj', 'patryk.jakubowski@example.com', '2025-05-11', 'kierownik', 7000.00),
(3, 'amanda_jablonska', 'amandaj', 'amanda.jablonska@example.com', '2025-05-11', 'Pracownik sklepu', 4900.00),
(4, 'adam_piekarski', 'adamp', 'adam.piekarski@example.com', '2025-05-11', 'admin', 6900.00),
(5, 'beata_mazurek', 'beatam', 'beata.mazurek@example.com', '2025-05-11', 'Pracownik sklepu', 4900.00),
(6, 'cezary_baranski', 'cezaryb', 'cezary.baranski@example.com', '2025-05-11', 'kierownik', 6500.00),
(7, 'diana_sikora', 'dianas', 'diana.sikora@example.com', '2025-05-11', 'admin', 7100.00),
(8, 'eryk_urban', 'eryku', 'eryk.urban@example.com', '2025-05-11', 'Pracownik sklepu', 5400.00);

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
-- Struktura tabeli dla tabeli `wiadomosci`
--

CREATE TABLE `wiadomosci` (
  `id` int(11) NOT NULL,
  `imie` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pytanie` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wiadomosci`
--

INSERT INTO `wiadomosci` (`id`, `imie`, `email`, `pytanie`) VALUES
(1, 'Andrzej', 'Andrrzej@gmail.com', 'Siemka mam pytanie');

-- --------------------------------------------------------

--
-- Zastąpiona struktura widoku `widok_najaktywniejsi_klienci`
-- (See below for the actual view)
--
CREATE TABLE `widok_najaktywniejsi_klienci` (
`id_klienta` int(11)
,`nazwa_uzytkownika` varchar(50)
,`email` varchar(100)
,`liczba_zamowien` bigint(21)
,`laczna_wartosc` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Zastąpiona struktura widoku `widok_najlepsze_produkty`
-- (See below for the actual view)
--
CREATE TABLE `widok_najlepsze_produkty` (
`id_produktu` int(11)
,`nazwa` varchar(255)
,`marka` varchar(100)
,`liczba_zamowien` bigint(21)
,`laczna_ilosc` decimal(32,0)
,`laczna_wartosc` decimal(42,2)
);

-- --------------------------------------------------------

--
-- Zastąpiona struktura widoku `widok_opinie_z_danymi`
-- (See below for the actual view)
--
CREATE TABLE `widok_opinie_z_danymi` (
`id_opinii` int(11)
,`ocena` int(11)
,`komentarz` text
,`data_opinii` timestamp
,`klient` varchar(50)
,`produkt` varchar(255)
,`marka` varchar(100)
);

-- --------------------------------------------------------

--
-- Zastąpiona struktura widoku `widok_pele_zamowienia`
-- (See below for the actual view)
--
CREATE TABLE `widok_pele_zamowienia` (
`id_zamowienia` int(11)
,`data_zamowienia` timestamp
,`klient` varchar(50)
,`produkt` varchar(255)
,`ilosc` int(11)
,`cena_jednostkowa` decimal(10,2)
,`wartosc_czesciowa` decimal(20,2)
,`kwota_calkowita` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Zastąpiona struktura widoku `widok_produkty_wedlug_kategorii`
-- (See below for the actual view)
--
CREATE TABLE `widok_produkty_wedlug_kategorii` (
`kategoria` varchar(100)
,`liczba_produktow` bigint(21)
,`najnizsza_cena` decimal(10,2)
,`najwyzsza_cena` decimal(10,2)
,`srednia_cena` decimal(14,6)
);

-- --------------------------------------------------------

--
-- Zastąpiona struktura widoku `widok_statystyki_produktow`
-- (See below for the actual view)
--
CREATE TABLE `widok_statystyki_produktow` (
`id_produktu` int(11)
,`nazwa` varchar(255)
,`marka` varchar(100)
,`cena` decimal(10,2)
,`liczba_zamowien` bigint(21)
,`laczna_ilosc` decimal(32,0)
,`laczna_wartosc` decimal(42,2)
);

-- --------------------------------------------------------

--
-- Zastąpiona struktura widoku `widok_szczegoly_zamowien`
-- (See below for the actual view)
--
CREATE TABLE `widok_szczegoly_zamowien` (
`id_elementu_zamowienia` int(11)
,`id_zamowienia` int(11)
,`produkt` varchar(255)
,`marka` varchar(100)
,`ilosc` int(11)
,`cena_jednostkowa` decimal(10,2)
,`wartosc_czesciowa` decimal(20,2)
);

-- --------------------------------------------------------

--
-- Zastąpiona struktura widoku `widok_zamowienia_z_klientami`
-- (See below for the actual view)
--
CREATE TABLE `widok_zamowienia_z_klientami` (
`id_zamowienia` int(11)
,`data_zamowienia` timestamp
,`kwota_calkowita` decimal(10,2)
,`id_klienta` int(11)
,`klient` varchar(50)
,`email` varchar(100)
);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zamowienia`
--

CREATE TABLE `zamowienia` (
  `id_zamowienia` int(11) NOT NULL,
  `id_klienta` int(11) DEFAULT NULL,
  `data_zamowienia` timestamp NOT NULL DEFAULT current_timestamp(),
  `kwota_calkowita` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zamowienia`
--

INSERT INTO `zamowienia` (`id_zamowienia`, `id_klienta`, `data_zamowienia`, `kwota_calkowita`) VALUES
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
(13, 29, '0000-00-00 00:00:00', 998.00),
(14, 29, '0000-00-00 00:00:00', 1497.00),
(15, 29, '0000-00-00 00:00:00', 998.00),
(16, 29, '0000-00-00 00:00:00', 1996.00),
(17, 29, '2025-05-17 15:23:29', 998.00),
(18, 29, '2025-05-17 15:25:28', 1497.00),
(19, 29, '2025-05-17 15:26:15', 1996.00),
(20, 29, '2025-05-17 15:30:48', 998.00),
(21, 29, '2025-05-17 15:37:09', 2495.00),
(22, 29, '2025-05-17 15:56:03', 1497.00);

-- --------------------------------------------------------

--
-- Struktura widoku `widok_najaktywniejsi_klienci`
--
DROP TABLE IF EXISTS `widok_najaktywniejsi_klienci`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `widok_najaktywniejsi_klienci`  AS SELECT `k`.`id_klienta` AS `id_klienta`, `k`.`nazwa_uzytkownika` AS `nazwa_uzytkownika`, `k`.`email` AS `email`, count(`z`.`id_zamowienia`) AS `liczba_zamowien`, sum(`z`.`kwota_calkowita`) AS `laczna_wartosc` FROM (`klienci` `k` join `zamowienia` `z` on(`k`.`id_klienta` = `z`.`id_klienta`)) GROUP BY `k`.`id_klienta`, `k`.`nazwa_uzytkownika`, `k`.`email` ORDER BY sum(`z`.`kwota_calkowita`) DESC ;

-- --------------------------------------------------------

--
-- Struktura widoku `widok_najlepsze_produkty`
--
DROP TABLE IF EXISTS `widok_najlepsze_produkty`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `widok_najlepsze_produkty`  AS SELECT `p`.`id_produktu` AS `id_produktu`, `p`.`nazwa` AS `nazwa`, `p`.`marka` AS `marka`, count(`ez`.`id_elementu_zamowienia`) AS `liczba_zamowien`, sum(`ez`.`ilosc`) AS `laczna_ilosc`, sum(`ez`.`ilosc` * `ez`.`cena_jednostkowa`) AS `laczna_wartosc` FROM (`produkty` `p` join `elementy_zamowienia` `ez` on(`p`.`id_produktu` = `ez`.`id_produktu`)) GROUP BY `p`.`id_produktu`, `p`.`nazwa`, `p`.`marka` ORDER BY sum(`ez`.`ilosc` * `ez`.`cena_jednostkowa`) DESC ;

-- --------------------------------------------------------

--
-- Struktura widoku `widok_opinie_z_danymi`
--
DROP TABLE IF EXISTS `widok_opinie_z_danymi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `widok_opinie_z_danymi`  AS SELECT `o`.`id_opinii` AS `id_opinii`, `o`.`ocena` AS `ocena`, `o`.`komentarz` AS `komentarz`, `o`.`data_opinii` AS `data_opinii`, `k`.`nazwa_uzytkownika` AS `klient`, `p`.`nazwa` AS `produkt`, `p`.`marka` AS `marka` FROM ((`opinie` `o` join `klienci` `k` on(`o`.`id_klienta` = `k`.`id_klienta`)) join `produkty` `p` on(`o`.`id_produktu` = `p`.`id_produktu`)) ;

-- --------------------------------------------------------

--
-- Struktura widoku `widok_pele_zamowienia`
--
DROP TABLE IF EXISTS `widok_pele_zamowienia`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `widok_pele_zamowienia`  AS SELECT `z`.`id_zamowienia` AS `id_zamowienia`, `z`.`data_zamowienia` AS `data_zamowienia`, `k`.`nazwa_uzytkownika` AS `klient`, `p`.`nazwa` AS `produkt`, `ez`.`ilosc` AS `ilosc`, `ez`.`cena_jednostkowa` AS `cena_jednostkowa`, `ez`.`ilosc`* `ez`.`cena_jednostkowa` AS `wartosc_czesciowa`, `z`.`kwota_calkowita` AS `kwota_calkowita` FROM (((`zamowienia` `z` join `klienci` `k` on(`z`.`id_klienta` = `k`.`id_klienta`)) join `elementy_zamowienia` `ez` on(`z`.`id_zamowienia` = `ez`.`id_zamowienia`)) join `produkty` `p` on(`ez`.`id_produktu` = `p`.`id_produktu`)) ;

-- --------------------------------------------------------

--
-- Struktura widoku `widok_produkty_wedlug_kategorii`
--
DROP TABLE IF EXISTS `widok_produkty_wedlug_kategorii`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `widok_produkty_wedlug_kategorii`  AS SELECT `produkty`.`kategoria` AS `kategoria`, count(`produkty`.`id_produktu`) AS `liczba_produktow`, min(`produkty`.`cena`) AS `najnizsza_cena`, max(`produkty`.`cena`) AS `najwyzsza_cena`, avg(`produkty`.`cena`) AS `srednia_cena` FROM `produkty` GROUP BY `produkty`.`kategoria` ;

-- --------------------------------------------------------

--
-- Struktura widoku `widok_statystyki_produktow`
--
DROP TABLE IF EXISTS `widok_statystyki_produktow`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `widok_statystyki_produktow`  AS SELECT `p`.`id_produktu` AS `id_produktu`, `p`.`nazwa` AS `nazwa`, `p`.`marka` AS `marka`, `p`.`cena` AS `cena`, count(`ez`.`id_elementu_zamowienia`) AS `liczba_zamowien`, sum(`ez`.`ilosc`) AS `laczna_ilosc`, sum(`ez`.`ilosc` * `ez`.`cena_jednostkowa`) AS `laczna_wartosc` FROM (`produkty` `p` left join `elementy_zamowienia` `ez` on(`p`.`id_produktu` = `ez`.`id_produktu`)) GROUP BY `p`.`id_produktu`, `p`.`nazwa`, `p`.`marka`, `p`.`cena` ;

-- --------------------------------------------------------

--
-- Struktura widoku `widok_szczegoly_zamowien`
--
DROP TABLE IF EXISTS `widok_szczegoly_zamowien`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `widok_szczegoly_zamowien`  AS SELECT `ez`.`id_elementu_zamowienia` AS `id_elementu_zamowienia`, `ez`.`id_zamowienia` AS `id_zamowienia`, `p`.`nazwa` AS `produkt`, `p`.`marka` AS `marka`, `ez`.`ilosc` AS `ilosc`, `ez`.`cena_jednostkowa` AS `cena_jednostkowa`, `ez`.`ilosc`* `ez`.`cena_jednostkowa` AS `wartosc_czesciowa` FROM (`elementy_zamowienia` `ez` join `produkty` `p` on(`ez`.`id_produktu` = `p`.`id_produktu`)) ;

-- --------------------------------------------------------

--
-- Struktura widoku `widok_zamowienia_z_klientami`
--
DROP TABLE IF EXISTS `widok_zamowienia_z_klientami`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `widok_zamowienia_z_klientami`  AS SELECT `z`.`id_zamowienia` AS `id_zamowienia`, `z`.`data_zamowienia` AS `data_zamowienia`, `z`.`kwota_calkowita` AS `kwota_calkowita`, `k`.`id_klienta` AS `id_klienta`, `k`.`nazwa_uzytkownika` AS `klient`, `k`.`email` AS `email` FROM (`zamowienia` `z` join `klienci` `k` on(`z`.`id_klienta` = `k`.`id_klienta`)) ;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `elementy_zamowienia`
--
ALTER TABLE `elementy_zamowienia`
  ADD PRIMARY KEY (`id_elementu_zamowienia`),
  ADD KEY `id_zamowienia` (`id_zamowienia`),
  ADD KEY `id_produktu` (`id_produktu`),
  ADD KEY `fk_elementy_zamowienia_klienci` (`id_klienta`);

--
-- Indeksy dla tabeli `klienci`
--
ALTER TABLE `klienci`
  ADD PRIMARY KEY (`id_klienta`),
  ADD UNIQUE KEY `nazwa_uzytkownika` (`nazwa_uzytkownika`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeksy dla tabeli `koszyki`
--
ALTER TABLE `koszyki`
  ADD PRIMARY KEY (`id_koszyka`),
  ADD KEY `id_klienta` (`id_klienta`),
  ADD KEY `id_produktu` (`id_produktu`);

--
-- Indeksy dla tabeli `opinie`
--
ALTER TABLE `opinie`
  ADD PRIMARY KEY (`id_opinii`),
  ADD KEY `id_produktu` (`id_produktu`),
  ADD KEY `id_klienta` (`id_klienta`);

--
-- Indeksy dla tabeli `pracownicy`
--
ALTER TABLE `pracownicy`
  ADD PRIMARY KEY (`id_pracownika`),
  ADD UNIQUE KEY `nazwa_uzytkownika` (`nazwa_uzytkownika`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeksy dla tabeli `produkty`
--
ALTER TABLE `produkty`
  ADD PRIMARY KEY (`id_produktu`);

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
  ADD KEY `id_klienta` (`id_klienta`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `elementy_zamowienia`
--
ALTER TABLE `elementy_zamowienia`
  MODIFY `id_elementu_zamowienia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `klienci`
--
ALTER TABLE `klienci`
  MODIFY `id_klienta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `koszyki`
--
ALTER TABLE `koszyki`
  MODIFY `id_koszyka` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `opinie`
--
ALTER TABLE `opinie`
  MODIFY `id_opinii` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pracownicy`
--
ALTER TABLE `pracownicy`
  MODIFY `id_pracownika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `produkty`
--
ALTER TABLE `produkty`
  MODIFY `id_produktu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
  ADD CONSTRAINT `elementy_zamowienia_ibfk_2` FOREIGN KEY (`id_produktu`) REFERENCES `produkty` (`id_produktu`),
  ADD CONSTRAINT `fk_elementy_zamowienia_klienci` FOREIGN KEY (`id_klienta`) REFERENCES `klienci` (`id_klienta`);

--
-- Constraints for table `koszyki`
--
ALTER TABLE `koszyki`
  ADD CONSTRAINT `koszyki_ibfk_1` FOREIGN KEY (`id_klienta`) REFERENCES `klienci` (`id_klienta`),
  ADD CONSTRAINT `koszyki_ibfk_2` FOREIGN KEY (`id_produktu`) REFERENCES `produkty` (`id_produktu`);

--
-- Constraints for table `opinie`
--
ALTER TABLE `opinie`
  ADD CONSTRAINT `opinie_ibfk_1` FOREIGN KEY (`id_produktu`) REFERENCES `produkty` (`id_produktu`),
  ADD CONSTRAINT `opinie_ibfk_2` FOREIGN KEY (`id_klienta`) REFERENCES `klienci` (`id_klienta`);

--
-- Constraints for table `zamowienia`
--
ALTER TABLE `zamowienia`
  ADD CONSTRAINT `zamowienia_ibfk_1` FOREIGN KEY (`id_klienta`) REFERENCES `klienci` (`id_klienta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
