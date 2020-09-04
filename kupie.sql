-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 04 Wrz 2020, 15:50
-- Wersja serwera: 10.3.22-MariaDB-0+deb10u1-log
-- Wersja PHP: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `db-user52513`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kategorie`
--

CREATE TABLE `kategorie` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `kategorie`
--

INSERT INTO `kategorie` (`id`, `nazwa`) VALUES
(2, 'Motoryzacja'),
(3, 'Nieruchomości'),
(4, 'Praca'),
(5, 'Dom i Ogród'),
(6, 'Elektronika'),
(7, 'Moda'),
(8, 'Rolnictwo'),
(9, 'Zwierzęta');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ogloszenia`
--

CREATE TABLE `ogloszenia` (
  `id` int(11) NOT NULL,
  `tytul` varchar(255) CHARACTER SET utf8 NOT NULL,
  `opis` text CHARACTER SET utf8 NOT NULL,
  `cena` int(11) NOT NULL,
  `id_wlasciciela` int(11) NOT NULL,
  `kontakt` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `kategoria` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `podkategoria` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `wojewodztwo` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `miejscowosc` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `img1` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `img2` varchar(255) DEFAULT NULL,
  `img3` varchar(255) DEFAULT NULL,
  `img4` varchar(255) DEFAULT NULL,
  `img5` varchar(255) DEFAULT NULL,
  `img6` varchar(255) DEFAULT NULL,
  `data_dodania` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `ogloszenia`
--

INSERT INTO `ogloszenia` (`id`, `tytul`, `opis`, `cena`, `id_wlasciciela`, `kontakt`, `kategoria`, `podkategoria`, `wojewodztwo`, `miejscowosc`, `img1`, `img2`, `img3`, `img4`, `img5`, `img6`, `data_dodania`) VALUES
(2, 'tytul', 'fdsfdsf', 100, 1, '', '', '', '', '', '', '', '', '', '', '', '2019-11-20 10:34:57'),
(3, 'tytul', '1 linia        ngklfdngkldsnsfndsklgnskd\r\n2    linia hnndhndhnjkldhnjldnh', 100, 1, '', '', '', '', '', '', '', '', '', '', '', '2019-11-20 10:34:57'),
(7, 'asd', 'asd', 555, 1, '', 'Motoryzacja', 'Samochody osobowe', 'małopolskie', 'nowy sacz', '', '', '', '', '', '', '2019-11-21 22:27:10'),
(15, 'asd', 'qwe', 33, 1, 's', 'Motoryzacja', 'Samochody osobowe', 'małopolskie', 'nowy sacz', '', '', '', '', '', '', '2019-11-21 22:27:10'),
(18, 'hgjhgjh', 'ghjhgjhg', 5345, 1, '54654', 'Motoryzacja', 'Motocykle i skutery', 'dolnośląskie', 'asd', '1925955668pawel.jpg', '', '', '', '', '', '2019-11-21 15:46:10'),
(19, 'hgjhgjh', 'ghjhgjhg', 5345, 1, '54654', 'Motoryzacja', 'Motocykle i skutery', 'dolnośląskie', 'asd', '1925955668pawel.jpg', '', '', '', '', '', '2019-11-21 15:46:30'),
(29, 'tramwaj', 'sprzedam tramwaj', 123444, 16, 'tel 123 123 123', 'Motoryzacja', 'Samochody osobowe', 'świętokrzyskie', 'zxc', '1635294837pawel.jpg', '1937465815pawel.jpg', '4122110710955281.jpg', '', '', '', '2019-11-22 17:26:46'),
(32, 'ksiazka', 'hary poter', 20, 16, 'tel 777666555', 'Dom i Ogród', 'inne', 'warmińsko-mazurskie', 'warszawa', '', '', '', '', '', '', '2019-11-22 17:39:49'),
(34, 'ksiazka', 'hary poter', 20, 16, 'tel 777666555', 'Dom i Ogród', 'inne', 'warmińsko-mazurskie', 'warszawa', '5271257525677718.jpg', '', '', '', '', '', '2019-11-22 17:41:27'),
(38, 'kosiarka', 'sprzedam kosiarke jjjj', 500, 1, 'tel. 505 444 222', 'Nieruchomości', 'inne', 'małopolskie', 'Nowy Sacz', '641898356pawel.jpg', '654557809pawel.jpg', '228944271pawel.png', '', '1399855351pawel.jpg', '', '2019-11-22 18:00:02'),
(39, 'krowa', 'sprzedam krowe', 44, 1, 'asd', 'Motoryzacja', 'Samochody osobowe', 'mazowieckie', 'ggg', '8403572986282083.jpg', '', '', '', '', '', '2019-11-22 18:00:33'),
(41, 'Test', 'To jest test', 500, 17, '+888888888\r\ntest@test.pl', 'Elektronika', 'inne', 'małopolskie', '', '6060938028941195.jpg', '', '', '', '', '', '2020-01-26 20:17:29'),
(43, 'test', 'opis', 500, 23, 'tel : 123123123', 'Nieruchomości', 'inne', 'lubelskie', 'nowy sacz', '9395043755927566.jpg', '', '', '', '', '', '2020-01-27 11:33:26');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `podkategorie`
--

CREATE TABLE `podkategorie` (
  `id` int(11) NOT NULL,
  `nazwa2` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `id_kategori` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `podkategorie`
--

INSERT INTO `podkategorie` (`id`, `nazwa2`, `id_kategori`) VALUES
(1, 'Samochody osobowe', 2),
(2, 'Motocykle i skutery', 2),
(3, 'inne', 5),
(4, 'inne', 6),
(5, 'inne', 7),
(6, 'inne', 2),
(7, 'inne', 3),
(8, 'inne', 4),
(9, 'inne', 8),
(10, 'inne', 9),
(11, 'Mieszkania', 3),
(12, 'Domy', 3),
(13, 'Działki', 3),
(14, 'podkategoria 2', 9);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id` int(11) NOT NULL,
  `login` varchar(20) CHARACTER SET utf8 NOT NULL,
  `haslo` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 NOT NULL,
  `potwierdzony` tinyint(1) NOT NULL,
  `uprawnienia` int(11) NOT NULL,
  `zablokowany` tinyint(1) NOT NULL,
  `powod_blokady` text CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id`, `login`, `haslo`, `email`, `potwierdzony`, `uprawnienia`, `zablokowany`, `powod_blokady`) VALUES
(1, 'pawel', '$2y$10$IptunJVg6CWXcnRRJLlXsORhsv6E8tlOyvAblXPaqiirCWwbG7Iny', 'asd@aaa.pl', 1, 1, 0, ''),
(15, 'qwe', '$2y$10$ArKsOh7t7rlkx1Cl1.UzzOJyZd5x2aWYoIhbeovdnNFQwojAerfiy', 'asd@dd.pl', 1, 1, 0, ''),
(16, 'admin', '$2y$10$gHElQZvD/TRpbvAH2SsRfe56/fqDUfYxU7BoAkIgSBPQoVrYvwk/S', 'zxc@asdmin.pl', 1, 2, 0, ''),
(17, 'superadmin', '$2y$10$.4kwkABv6RdJePg4cSJHRe/WbXh0cqEQTFEPqxJeJIXK6gav3awsq', 'zzz@zzz.pl', 1, 3, 0, ''),
(18, 'asd', '$2y$10$Q0dk4p6WoDJB2XhrmrvXUu6iqJ571jA2PCkSZWLMYNhSqeXnJtf3C', 'p4wel09@gmail.com', 1, 1, 0, ''),
(19, 'q23', '$2y$10$3sSWLwpiR79av01wOygVSefuI2SeXgPH0bvnhKU0RB4YoatbXsjE2', 'pocab68730@hiwave.org', 1, 1, 0, ''),
(20, 'tester', '$2y$10$QChpuWEsivZr8jmG3Q/JieNhXjbv8oeSa8BuJoBe7beKUX/TvDoSG', 'barticf@o2.pl', 0, 1, 0, ''),
(21, 'ggg', '$2y$10$nSas0fQW9OqTnSMfPGW7/ehDlc9isAc3ShJ/6SMH9LT.u9nAj/cUW', 'c63zfc2s9iem@10minut.xyz', 0, 1, 0, ''),
(22, 'mmm', '$2y$10$RAOLaklyYzP.PxV2wvrsqem8DV.K2sujnaVrd7DV46oebHG3aoCP6', 'costepoydu@enayu.com', 1, 1, 0, ''),
(23, 'kkk', '$2y$10$rWULv..IhzFY75sbEisdauwQZDptHrjqH3vZAYqjRjsg3L.jYoxb.', 'kumledodre@enayu.com', 1, 1, 0, '');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `ogloszenia`
--
ALTER TABLE `ogloszenia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_wlasciciela` (`id_wlasciciela`);

--
-- Indeksy dla tabeli `podkategorie`
--
ALTER TABLE `podkategorie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT dla tabeli `ogloszenia`
--
ALTER TABLE `ogloszenia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT dla tabeli `podkategorie`
--
ALTER TABLE `podkategorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `ogloszenia`
--
ALTER TABLE `ogloszenia`
  ADD CONSTRAINT `ogloszenia_ibfk_1` FOREIGN KEY (`id_wlasciciela`) REFERENCES `uzytkownicy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `podkategorie`
--
ALTER TABLE `podkategorie`
  ADD CONSTRAINT `podkategorie_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategorie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
