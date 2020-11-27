-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Počítač: mysql57.websupport.sk:3311
-- Vytvořeno: Pát 27. lis 2020, 19:08
-- Verze serveru: 5.7.25-28-log
-- Verze PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `fit_iis`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `Employment`
--

CREATE TABLE `Employment` (
  `id` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `idhotel` int(11) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `Employment`
--

INSERT INTO `Employment` (`id`, `iduser`, `idhotel`, `position`) VALUES
(3, 1, 183, 1),
(4, 1, 184, 1),
(5, 1, 185, 1),
(6, 1, 186, 1),
(7, 102, 183, 2),
(8, 102, 184, 2),
(9, 102, 185, 2),
(10, 102, 186, 2),
(11, 103, 183, 3),
(12, 103, 184, 3),
(13, 103, 185, 3),
(14, 103, 186, 3);

-- --------------------------------------------------------

--
-- Struktura tabulky `Equipment`
--

CREATE TABLE `Equipment` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `Equipment`
--

INSERT INTO `Equipment` (`id`, `name`) VALUES
(1, 'Television'),
(2, 'Wi-Fi'),
(3, 'Jacuzzi'),
(4, 'Minibar'),
(5, 'Disinfection'),
(6, 'Shower'),
(7, 'Toilet'),
(8, 'Bathtub'),
(9, 'Socket'),
(10, 'Balcony');

-- --------------------------------------------------------

--
-- Struktura tabulky `Hotel`
--

CREATE TABLE `Hotel` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `country` varchar(30) NOT NULL,
  `city` varchar(50) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `street` varchar(50) NOT NULL,
  `stars` int(11) NOT NULL,
  `rating` float DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `Hotel`
--

INSERT INTO `Hotel` (`id`, `name`, `country`, `city`, `zip`, `street`, `stars`, `rating`, `description`) VALUES
(183, 'IIS hotel', 'Czechia', 'Brno', '612 00', 'Božetěchova 1/2', 3, NULL, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Integer lacinia. Proin pede metus, vulputate nec, fermentum fringilla, vehicula vitae, justo. Duis bibendum, lectus ut viverra rhoncus, dolor nunc faucibus libero, eget facilisis enim ipsum id lacus.'),
(184, 'Burj', 'UAE', 'Dubai', '111 11', 'Jumeirah', 5, NULL, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Integer lacinia. Proin pede metus, vulputate nec, fermentum fringilla, vehicula vitae, justo. Duis bibendum, lectus ut viverra rhoncus, dolor nunc faucibus libero, eget facilisis enim ipsum id lacus.'),
(185, 'Hotel New York', 'USA', 'New York', '100 01', 'Fulton St', 5, NULL, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Integer lacinia. Proin pede metus, vulputate nec, fermentum fringilla, vehicula vitae, justo. Duis bibendum, lectus ut viverra rhoncus, dolor nunc faucibus libero, eget facilisis enim ipsum id lacus.'),
(186, 'The Palm', 'UAE', 'Dubai', '1001 11', 'Crescent', 5, NULL, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Integer lacinia. Proin pede metus, vulputate nec, fermentum fringilla, vehicula vitae, justo. Duis bibendum, lectus ut viverra rhoncus, dolor nunc faucibus libero, eget facilisis enim ipsum id lacus.');

--
-- Spouště `Hotel`
--
DELIMITER $$
CREATE TRIGGER `AFterDeletePhoto` AFTER DELETE ON `Hotel` FOR EACH ROW DELETE FROM Photos WHERE hotel_id=Old.id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `OnDelete` AFTER DELETE ON `Hotel` FOR EACH ROW DELETE FROM RoomType WHERE hotel_id=Old.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabulky `Photos`
--

CREATE TABLE `Photos` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `path` varchar(241) NOT NULL,
  `type` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `Photos`
--

INSERT INTO `Photos` (`id`, `hotel_id`, `room_id`, `path`, `type`) VALUES
(185, 183, NULL, 'uploads/hid_183_gallery_hotel-1749602_1920.jpg', 'Gallery'),
(186, 183, NULL, 'uploads/hid_183_titleImage_hotel-1749602_1920.jpg', 'Title'),
(187, 184, NULL, 'uploads/hid_184_gallery_burj-al-arab-2624317_1920.jpg', 'Gallery'),
(188, 184, NULL, 'uploads/hid_184_titleImage_burj-al-arab-2624317_1920.jpg', 'Title'),
(189, 185, NULL, 'uploads/hid_185_gallery_new-york-532691_1920.jpg', 'Gallery'),
(190, 185, NULL, 'uploads/hid_185_titleImage_new-york-532691_1920.jpg', 'Title'),
(191, 186, NULL, 'uploads/hid_186_gallery_the-palm-962785_1920.jpg', 'Gallery'),
(192, 186, NULL, 'uploads/hid_186_titleImage_the-palm-962785_1920.jpg', 'Title');

-- --------------------------------------------------------

--
-- Struktura tabulky `Reservation`
--

CREATE TABLE `Reservation` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `arrival` date NOT NULL,
  `departure` date NOT NULL,
  `stav` int(11) DEFAULT '1' COMMENT 'REcepcni meni stav',
  `jistina_zaplaceno` tinyint(1) DEFAULT NULL,
  `check_in` datetime DEFAULT NULL,
  `check_out` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `ReservationNonReg`
--

CREATE TABLE `ReservationNonReg` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone_number` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `Reviews`
--

CREATE TABLE `Reviews` (
  `id` int(11) NOT NULL,
  `hotel_id` int(128) NOT NULL,
  `user_id` int(128) NOT NULL,
  `note` text NOT NULL,
  `rating` int(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `Rights`
--

CREATE TABLE `Rights` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `Rights`
--

INSERT INTO `Rights` (`id`, `title`) VALUES
(0, 'NONE'),
(1, 'Admin'),
(2, 'Owner'),
(3, 'Receptionist'),
(4, 'User');

-- --------------------------------------------------------

--
-- Struktura tabulky `Room`
--

CREATE TABLE `Room` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `beds` int(11) NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `Room`
--

INSERT INTO `Room` (`id`, `hotel_id`, `number`, `beds`, `type`) VALUES
(2236, 183, 1, 2, 67),
(2237, 183, 2, 2, 67),
(2238, 183, 3, 2, 67),
(2239, 183, 4, 2, 67),
(2240, 183, 5, 2, 67),
(2241, 183, 6, 2, 67),
(2242, 183, 7, 2, 67),
(2243, 183, 8, 2, 67),
(2244, 183, 9, 2, 67),
(2245, 183, 10, 2, 67),
(2246, 183, 11, 2, 67),
(2247, 183, 12, 2, 67),
(2248, 183, 13, 2, 67),
(2249, 183, 14, 2, 67),
(2250, 183, 15, 2, 67),
(2251, 183, 16, 2, 67),
(2252, 183, 17, 2, 67),
(2253, 183, 18, 2, 67),
(2254, 183, 19, 2, 67),
(2255, 183, 20, 2, 67),
(2256, 183, 21, 2, 67),
(2257, 183, 22, 2, 67),
(2258, 183, 23, 2, 67),
(2259, 183, 24, 2, 67),
(2260, 183, 25, 2, 67),
(2261, 184, 1, 2, 68),
(2262, 184, 2, 2, 68),
(2263, 184, 3, 2, 68),
(2264, 184, 4, 2, 68),
(2265, 184, 5, 2, 68),
(2266, 184, 6, 2, 68),
(2267, 184, 7, 2, 68),
(2268, 184, 8, 2, 68),
(2269, 184, 9, 2, 68),
(2270, 184, 10, 2, 68),
(2271, 184, 11, 2, 68),
(2272, 184, 12, 2, 68),
(2273, 184, 13, 2, 68),
(2274, 184, 14, 2, 68),
(2275, 184, 15, 2, 68),
(2276, 184, 16, 2, 68),
(2277, 184, 17, 2, 68),
(2278, 184, 18, 2, 68),
(2279, 184, 19, 2, 68),
(2280, 184, 20, 2, 68),
(2281, 185, 1, 2, 69),
(2282, 185, 2, 2, 69),
(2283, 185, 3, 2, 69),
(2284, 185, 4, 2, 69),
(2285, 185, 5, 2, 69),
(2286, 185, 6, 2, 69),
(2287, 185, 7, 2, 69),
(2288, 185, 8, 2, 69),
(2289, 185, 9, 2, 69),
(2290, 185, 10, 2, 69),
(2291, 186, 1, 3, 70),
(2292, 186, 2, 3, 70),
(2293, 184, 21, 2, 71),
(2294, 184, 22, 2, 71),
(2295, 184, 23, 2, 71),
(2296, 184, 24, 2, 71),
(2297, 184, 25, 2, 71),
(2298, 184, 26, 2, 71),
(2299, 184, 27, 2, 71),
(2300, 184, 28, 2, 71),
(2301, 184, 29, 2, 71),
(2302, 184, 30, 2, 71),
(2303, 184, 31, 2, 71),
(2304, 184, 32, 2, 71);

-- --------------------------------------------------------

--
-- Struktura tabulky `RoomEquipment`
--

CREATE TABLE `RoomEquipment` (
  `id` int(11) NOT NULL,
  `roomType` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `RoomEquipment`
--

INSERT INTO `RoomEquipment` (`id`, `roomType`, `equipment_id`) VALUES
(137, 67, 2),
(138, 67, 5),
(139, 67, 9),
(140, 68, 1),
(141, 68, 2),
(142, 68, 4),
(143, 68, 5),
(144, 68, 6),
(145, 68, 7),
(146, 68, 8),
(147, 68, 9),
(148, 69, 1),
(149, 69, 2),
(150, 69, 3),
(151, 69, 4),
(152, 69, 5),
(153, 69, 6),
(154, 69, 7),
(155, 69, 8),
(156, 69, 9),
(157, 70, 1),
(158, 70, 2),
(159, 70, 3),
(160, 70, 4),
(161, 70, 5),
(162, 70, 6),
(163, 70, 7),
(164, 70, 8),
(165, 70, 9),
(166, 70, 10),
(167, 71, 1),
(168, 71, 2),
(169, 71, 3),
(170, 71, 4),
(171, 71, 5),
(172, 71, 6),
(173, 71, 7),
(174, 71, 8),
(175, 71, 9),
(176, 71, 10);

-- --------------------------------------------------------

--
-- Struktura tabulky `RoomType`
--

CREATE TABLE `RoomType` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `type` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `pricePerBed` int(11) NOT NULL,
  `principal` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `RoomType`
--

INSERT INTO `RoomType` (`id`, `hotel_id`, `type`, `description`, `pricePerBed`, `principal`) VALUES
(67, 183, 'Student', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Integer lacinia. Proin pede metus, vulputate nec, fermentum fringilla, vehicula vitae, justo. Duis bibendum, lectus ut viverra rhoncus, dolor nunc faucibus libero, eget facilisis enim ipsum id lacus.', 10, 5),
(68, 184, 'Business', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Integer lacinia. Proin pede metus, vulputate nec, fermentum fringilla, vehicula vitae, justo. Duis bibendum, lectus ut viverra rhoncus, dolor nunc faucibus libero, eget facilisis enim ipsum id lacus.', 2000, 150),
(69, 185, 'Ministry', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Integer lacinia. Proin pede metus, vulputate nec, fermentum fringilla, vehicula vitae, justo. Duis bibendum, lectus ut viverra rhoncus, dolor nunc faucibus libero, eget facilisis enim ipsum id lacus.', 1000, 0),
(70, 186, 'Presidential', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Integer lacinia. Proin pede metus, vulputate nec, fermentum fringilla, vehicula vitae, justo. Duis bibendum, lectus ut viverra rhoncus, dolor nunc faucibus libero, eget facilisis enim ipsum id lacus.', 4000, 1000),
(71, 184, 'Luxurious', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Integer lacinia. Proin pede metus, vulputate nec, fermentum fringilla, vehicula vitae, justo. Duis bibendum, lectus ut viverra rhoncus, dolor nunc faucibus libero, eget facilisis enim ipsum id lacus.', 2500, 800);

-- --------------------------------------------------------

--
-- Struktura tabulky `State`
--

CREATE TABLE `State` (
  `id` int(11) NOT NULL,
  `state` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `State`
--

INSERT INTO `State` (`id`, `state`) VALUES
(1, 'Pending'),
(2, 'Awaiting payment'),
(3, 'Principal paid'),
(4, 'Confirmed'),
(5, 'Canceled'),
(6, 'Finished');

-- --------------------------------------------------------

--
-- Struktura tabulky `User`
--

CREATE TABLE `User` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `surname` varchar(120) NOT NULL,
  `phone_number` varchar(120) DEFAULT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(128) DEFAULT NULL,
  `rights` int(11) NOT NULL,
  `address` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `User`
--

INSERT INTO `User` (`id`, `name`, `surname`, `phone_number`, `email`, `password`, `rights`, `address`) VALUES
(0, 'Nonregistred', 'Nonregistred', '', 'nonregistred@planaxis.space', '$2y$10$zF6zLIYrOzoRJMvpqsoqo.nyYYmfO12/rwUzWpzSvuQ2e1MUZQhWq', 0, NULL),
(1, 'Admin', 'Adminer', '111 111 111', 'admin@planaxis.space', '$2y$10$Er8SOg1uyuG.CwnBmQahue6y3PTjr1L0eHHenEjOpJGC59GJCOI4m', 1, 'Testerova 2'),
(102, 'Owner', 'Owneros', '444 556 777', 'owner@planaxis.space', '$2y$10$3mwxLUvvTWFjvZcNS5sJZuXJxC.VZTbWbmJkuhWHqhHdXv2xqNhyC', 2, ''),
(103, 'Receptionist', 'Reception', '789 541 264', 'receptionist@planaxis.space', '$2y$10$NnThCHkpz2oAhneD5Qaf1elnpJCu4ULJ.jw4sc2ihyOa/CFi/CR5q', 3, '');

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `Employment`
--
ALTER TABLE `Employment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Owns_ibfk_1` (`iduser`),
  ADD KEY `Owns_ibfk_2` (`idhotel`),
  ADD KEY `position` (`position`);

--
-- Klíče pro tabulku `Equipment`
--
ALTER TABLE `Equipment`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `Hotel`
--
ALTER TABLE `Hotel`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `Photos`
--
ALTER TABLE `Photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `PhotosHotel` (`hotel_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Klíče pro tabulku `Reservation`
--
ALTER TABLE `Reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`),
  ADD KEY `Reservation_ibfk_3` (`stav`),
  ADD KEY `Reservation_ibfk_2` (`room_id`),
  ADD KEY `ReservationUser` (`user_id`);

--
-- Klíče pro tabulku `ReservationNonReg`
--
ALTER TABLE `ReservationNonReg`
  ADD KEY `ReservationNonReg_ibfk_1` (`id`);

--
-- Klíče pro tabulku `Reviews`
--
ALTER TABLE `Reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ReviewsHotel` (`hotel_id`),
  ADD KEY `ReviewsRating` (`user_id`);

--
-- Klíče pro tabulku `Rights`
--
ALTER TABLE `Rights`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `Room`
--
ALTER TABLE `Room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Room_ibfk_1` (`hotel_id`),
  ADD KEY `type` (`type`);

--
-- Klíče pro tabulku `RoomEquipment`
--
ALTER TABLE `RoomEquipment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `RoomEquipment_ibfk_1` (`equipment_id`),
  ADD KEY `RoomEquipment_ibfk_2` (`roomType`);

--
-- Klíče pro tabulku `RoomType`
--
ALTER TABLE `RoomType`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `State`
--
ALTER TABLE `State`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD KEY `User_ibfk_1` (`rights`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `Employment`
--
ALTER TABLE `Employment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pro tabulku `Equipment`
--
ALTER TABLE `Equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pro tabulku `Hotel`
--
ALTER TABLE `Hotel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT pro tabulku `Photos`
--
ALTER TABLE `Photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT pro tabulku `Reservation`
--
ALTER TABLE `Reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=276;

--
-- AUTO_INCREMENT pro tabulku `Reviews`
--
ALTER TABLE `Reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pro tabulku `Rights`
--
ALTER TABLE `Rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pro tabulku `Room`
--
ALTER TABLE `Room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2305;

--
-- AUTO_INCREMENT pro tabulku `RoomEquipment`
--
ALTER TABLE `RoomEquipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT pro tabulku `RoomType`
--
ALTER TABLE `RoomType`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT pro tabulku `State`
--
ALTER TABLE `State`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pro tabulku `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `Employment`
--
ALTER TABLE `Employment`
  ADD CONSTRAINT `Employment_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Employment_ibfk_2` FOREIGN KEY (`idhotel`) REFERENCES `Hotel` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Employment_ibfk_3` FOREIGN KEY (`position`) REFERENCES `Rights` (`id`);

--
-- Omezení pro tabulku `Photos`
--
ALTER TABLE `Photos`
  ADD CONSTRAINT `PhotosHotel` FOREIGN KEY (`hotel_id`) REFERENCES `Hotel` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Photos_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `Room` (`id`);

--
-- Omezení pro tabulku `Reservation`
--
ALTER TABLE `Reservation`
  ADD CONSTRAINT `ReservationUser` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `Reservation_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `Hotel` (`id`),
  ADD CONSTRAINT `Reservation_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `Room` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Reservation_ibfk_3` FOREIGN KEY (`stav`) REFERENCES `State` (`id`) ON UPDATE CASCADE;

--
-- Omezení pro tabulku `ReservationNonReg`
--
ALTER TABLE `ReservationNonReg`
  ADD CONSTRAINT `ReservationNonReg_ibfk_1` FOREIGN KEY (`id`) REFERENCES `Reservation` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `Reviews`
--
ALTER TABLE `Reviews`
  ADD CONSTRAINT `ReviewsHotel` FOREIGN KEY (`hotel_id`) REFERENCES `Hotel` (`id`),
  ADD CONSTRAINT `ReviewsRating` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`);

--
-- Omezení pro tabulku `Room`
--
ALTER TABLE `Room`
  ADD CONSTRAINT `Room_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `Hotel` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Room_ibfk_2` FOREIGN KEY (`type`) REFERENCES `RoomType` (`id`);

--
-- Omezení pro tabulku `RoomEquipment`
--
ALTER TABLE `RoomEquipment`
  ADD CONSTRAINT `RoomEquipment_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `Equipment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `RoomEquipment_ibfk_2` FOREIGN KEY (`roomType`) REFERENCES `RoomType` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `User`
--
ALTER TABLE `User`
  ADD CONSTRAINT `User_ibfk_1` FOREIGN KEY (`rights`) REFERENCES `Rights` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
