-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Počítač: mysql57.websupport.sk:3311
-- Vytvořeno: Stř 28. říj 2020, 15:31
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
-- Struktura tabulky `Equipment`
--

CREATE TABLE `Equipment` (
  `id` int(11) NOT NULL,
  `hotel_id` int(128) NOT NULL,
  `room_id` int(128) NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(0, 'NONE', '', '', '0', '', 0, NULL, ''),
(1, 'Hilton Prague', 'Czechia', 'Prague', '186 00', 'Pobřežní 1', 4, 9.1, 'Situated right in the heart of Nitra, Hotel Centrum offers beautiful views of the historic centre, the castle, the theatre and the pedestrian zone. Free Wi-Fi is available in the entire hotel. Guests receive a welcome drink.All air-conditioned rooms feature satellite TV and a minibar, and include a private bathroom with a shower or a bath.Guests can start their day with a free buffet breakfast or they can relax in a bar. The Centrum Hotel also offers a guarded parking area free of charge and there is also a charging station for electric cars available.Couples particularly like the location — they rated it 9.6 for a two-person trip.We speak your language!Hotel Centrum has been welcoming Booking.com guests since 11 Aug 2009.'),
(78, 'Four Seasons', 'Czech', 'Prague', '111 11', 'Majnhartova', 4, NULL, 'Top'),
(107, 'Hotelos', 'COuntry', 'Zlinos', '99999', 'Street', 2, NULL, 'Description'),
(109, 'Hotelwithfoto', 'Poland', 'KRAKOW', '091000', 'KRAKOWMAIN', 4, NULL, 'Description'),
(110, 'HeavenHotel', 'Germany', 'BERLIN', '88 888', 'Streety', 4, NULL, 'FoUkNuHoTaM'),
(115, 'sasaas', 'sasaddsa', 'asdsadsda', '32424', 'ssfdsf', 5, NULL, 'sdfdsfdsfds'),
(116, 'KUBAHOTEL', 'Finlandia', 'Oslo', '102993', 'Streeets of the street', 0, NULL, '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
(123, 'Hotel with rooms', 'Cntry', 'Citys', '92929', 'sjdjdbfsdb', 2, NULL, 'kdjsbkfbksdfk'),
(124, 'Mynewhotel', 'Innewcountry', 'Innewcity', '12345', 'Jeje', 2, NULL, 'POPIS');

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
-- Struktura tabulky `Owns`
--

CREATE TABLE `Owns` (
  `id` int(11) NOT NULL,
  `idowner` int(11) NOT NULL,
  `idhotel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `Owns`
--

INSERT INTO `Owns` (`id`, `idowner`, `idhotel`) VALUES
(1, 3, 1),
(4, 3, 78),
(6, 3, 124);

-- --------------------------------------------------------

--
-- Struktura tabulky `Photos`
--

CREATE TABLE `Photos` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `info` varchar(250) NOT NULL,
  `path` varchar(241) NOT NULL,
  `type` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `Photos`
--

INSERT INTO `Photos` (`id`, `hotel_id`, `room_id`, `info`, `path`, `type`) VALUES
(5, 107, NULL, '', 'uploads/hid_107_gallery_32736014784_8413db2be6_o.jpg', 'Gallery'),
(6, 107, NULL, '', 'uploads/hid_107_gallery_32764893633_1e5e316eb0_o.jpg', 'Gallery'),
(7, 107, NULL, '', 'uploads/hid_107_gallery_bealach-verse-mDuluxr50Ew-unsplash.jpg', 'Gallery'),
(18, 109, NULL, '', 'uploads/hid_109_gallery_585649591-black-wallpaper-4k.jpg', 'Gallery'),
(19, 109, NULL, '', 'uploads/hid_109_gallery_32736014784_8413db2be6_o.jpg', 'Gallery'),
(20, 109, NULL, '', 'uploads/hid_109_gallery_32764893633_1e5e316eb0_o.jpg', 'Gallery'),
(21, 109, NULL, '', 'uploads/hid_109_gallery_bealach-verse-mDuluxr50Ew-unsplash.jpg', 'Gallery'),
(22, 109, NULL, '', 'uploads/hid_109_gallery_Horizon-Zero-Dawn-4K-Ultra-HD-Wallpaper.jpg', 'Gallery'),
(23, 109, NULL, '', 'uploads/hid_109_gallery_iw7jaix54u911.png', 'Gallery'),
(24, 109, NULL, '', 'uploads/hid_109_gallery_main-stage-fireworks-tomorrowland-2018.jpg', 'Gallery'),
(30, 78, NULL, '', 'uploads/hid_78_gallery_iw7jaix54u911.png', 'Gallery'),
(31, 78, NULL, '', 'uploads/hid_78_gallery_john-fowler-5bv-_tIwHFk-unsplash.jpg', 'Gallery'),
(32, 78, NULL, '', 'uploads/hid_78_gallery_john-fowler-OR3BESdSx70-unsplash.jpg', 'Gallery'),
(44, 115, NULL, '', 'uploads/hid_115_gallery_32736014784_8413db2be6_o.jpg', 'Gallery'),
(45, 115, NULL, '', 'uploads/hid_115_gallery_32764893633_1e5e316eb0_o.jpg', 'Gallery'),
(46, 115, NULL, '', 'uploads/hid_115_gallery_iw7jaix54u911.png', 'Gallery'),
(48, 110, NULL, '', 'uploads/hid_110_gallery_FLAMING MOUNTAIN.JPG', 'Gallery'),
(50, 116, NULL, '', 'uploads/hid_116_gallery_image.jpg', 'Gallery'),
(51, 116, NULL, '', 'uploads/hid_116_gallery_234px-Flag_of_Israel.svg.png', 'Gallery'),
(65, 1, NULL, '', 'uploads/hid_1_gallery_32736014784_8413db2be6_o.jpg', 'Gallery'),
(71, 1, NULL, '', 'uploads/hid_1_titleImage_iw7jaix54u911.png', 'Title'),
(72, 78, NULL, '', 'uploads/hid_78_titleImage_main-stage-fireworks-tomorrowland-2018.jpg', 'Title'),
(73, 107, NULL, '', 'uploads/hid_107_titleImage_tim-swaan-eOpewngf68w-unsplash.jpg', 'Title'),
(74, 109, NULL, '', 'uploads/hid_109_titleImage_twilight-main-stage-tomorrowland-2018.jpg', 'Title'),
(75, 110, NULL, '', 'uploads/hid_110_titleImage_FLAMING MOUNTAIN.JPG', 'Title'),
(76, 115, NULL, '', 'uploads/hid_115_titleImage_Horizon-Zero-Dawn-4K-Ultra-HD-Wallpaper.jpg', 'Title'),
(77, 116, NULL, '', 'uploads/hid_116_titleImage_trevor-cole-oy8ESgXNRsw-unsplash.jpg', 'Title'),
(84, 123, NULL, '', 'uploads/hid_123_gallery_john-lee-oMneOBYhJxY-unsplash.jpg', 'Gallery'),
(85, 123, NULL, '', 'uploads/hid_123_gallery_ricardo-gomez-angel-TkSi_p-5HR0-unsplash.jpg', 'Gallery'),
(87, 123, NULL, 'title', 'uploads/hid_123_titleImage_iw7jaix54u911.png', 'Title'),
(88, 124, NULL, '', 'uploads/hid_124_gallery_Images-Dark-Wallpapers-HD.jpeg', 'Gallery'),
(89, 124, NULL, '', 'uploads/hid_124_titleImage_john-fowler-OR3BESdSx70-unsplash.jpg', 'Title');

-- --------------------------------------------------------

--
-- Struktura tabulky `Reservation`
--

CREATE TABLE `Reservation` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `arrival` date NOT NULL,
  `departure` date NOT NULL,
  `stav` int(11) DEFAULT '1' COMMENT 'REcepcni meni stav',
  `jistina_zaplaceno` tinyint(1) DEFAULT NULL,
  `check_in` datetime DEFAULT NULL,
  `check_out` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `Reservation`
--

INSERT INTO `Reservation` (`id`, `hotel_id`, `room_id`, `user_id`, `arrival`, `departure`, `stav`, `jistina_zaplaceno`, `check_in`, `check_out`) VALUES
(5, 1, 1554, NULL, '2020-10-28', '2020-10-29', 1, NULL, NULL, NULL),
(6, 1, 1554, NULL, '2020-10-28', '2020-10-29', 1, NULL, NULL, NULL),
(7, 1, 1554, NULL, '2020-10-28', '2020-10-29', 1, NULL, NULL, NULL),
(8, 1, 1554, NULL, '2020-10-28', '2020-10-29', 1, NULL, NULL, NULL),
(9, 1, 1554, NULL, '2020-10-28', '2020-10-29', 1, NULL, NULL, NULL),
(10, 1, 1554, NULL, '2020-10-28', '2020-10-29', 1, NULL, NULL, NULL),
(11, 1, 1554, NULL, '2020-10-28', '2020-10-29', 1, NULL, NULL, NULL),
(12, 1, 1554, NULL, '2020-10-28', '2020-10-29', 1, NULL, NULL, NULL),
(13, 1, 1554, NULL, '2020-10-28', '2020-10-29', 1, NULL, NULL, NULL),
(14, 1, 1554, NULL, '2020-10-28', '2020-10-29', 1, NULL, NULL, NULL),
(15, 1, 1554, NULL, '2020-10-28', '2020-10-29', 1, NULL, NULL, NULL),
(16, 1, 1554, NULL, '2020-10-28', '2020-10-29', 1, NULL, NULL, NULL),
(17, 1, 1554, NULL, '2020-10-28', '2020-10-29', 1, NULL, NULL, NULL),
(18, 1, 1554, NULL, '2020-10-28', '2020-10-29', 1, NULL, NULL, NULL);

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

--
-- Vypisuji data pro tabulku `Reviews`
--

INSERT INTO `Reviews` (`id`, `hotel_id`, `user_id`, `note`, `rating`) VALUES
(1, 1, 4, 'ppci', 5),
(2, 1, 3, 'meh', 4);

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
(932, 123, 0, 8, 2),
(933, 123, 1, 8, 2),
(934, 123, 2, 8, 2),
(935, 123, 3, 8, 2),
(936, 123, 4, 8, 2),
(937, 123, 5, 8, 2),
(938, 123, 6, 8, 2),
(939, 123, 7, 8, 2),
(940, 123, 8, 8, 2),
(941, 123, 9, 8, 2),
(942, 123, 10, 8, 2),
(943, 123, 11, 8, 2),
(944, 123, 12, 8, 2),
(945, 123, 13, 8, 2),
(946, 123, 14, 8, 2),
(947, 123, 15, 8, 2),
(948, 123, 16, 8, 2),
(949, 123, 17, 8, 2),
(950, 123, 18, 8, 2),
(951, 123, 19, 8, 2),
(952, 123, 20, 8, 2),
(953, 123, 21, 8, 2),
(954, 123, 22, 8, 2),
(955, 123, 23, 8, 2),
(956, 123, 24, 8, 2),
(957, 123, 25, 8, 2),
(958, 123, 26, 8, 2),
(959, 123, 27, 8, 2),
(960, 123, 28, 8, 2),
(961, 123, 29, 8, 2),
(962, 123, 30, 8, 2),
(963, 123, 31, 8, 2),
(964, 123, 32, 8, 2),
(965, 123, 33, 8, 2),
(966, 123, 34, 8, 2),
(967, 123, 35, 8, 2),
(968, 123, 36, 8, 2),
(969, 123, 37, 8, 2),
(970, 123, 38, 8, 2),
(971, 123, 39, 8, 2),
(972, 123, 40, 8, 2),
(973, 123, 41, 8, 2),
(974, 123, 42, 8, 2),
(975, 123, 43, 8, 2),
(976, 123, 44, 8, 2),
(977, 123, 45, 8, 2),
(978, 123, 46, 8, 2),
(979, 123, 47, 8, 2),
(980, 123, 48, 8, 2),
(981, 123, 49, 8, 2),
(982, 123, 50, 8, 2),
(983, 123, 51, 8, 2),
(984, 123, 52, 8, 2),
(985, 123, 53, 8, 2),
(986, 123, 54, 8, 2),
(987, 123, 55, 8, 2),
(988, 123, 56, 8, 2),
(989, 123, 57, 8, 2),
(990, 123, 58, 8, 2),
(991, 123, 59, 8, 2),
(992, 123, 60, 8, 2),
(993, 123, 61, 8, 2),
(994, 123, 62, 8, 2),
(995, 123, 63, 8, 2),
(996, 123, 64, 8, 2),
(997, 123, 65, 8, 2),
(998, 123, 66, 8, 2),
(999, 123, 67, 8, 2),
(1000, 123, 68, 8, 2),
(1001, 123, 69, 8, 2),
(1002, 123, 70, 8, 2),
(1003, 123, 71, 8, 2),
(1004, 123, 72, 8, 2),
(1005, 123, 73, 8, 2),
(1006, 123, 74, 8, 2),
(1007, 123, 75, 8, 2),
(1008, 123, 76, 8, 2),
(1009, 123, 77, 8, 2),
(1010, 123, 78, 8, 2),
(1011, 123, 79, 8, 2),
(1012, 123, 80, 8, 2),
(1013, 123, 81, 8, 2),
(1014, 123, 82, 8, 2),
(1015, 123, 83, 8, 2),
(1016, 123, 84, 8, 2),
(1017, 123, 85, 8, 2),
(1018, 123, 86, 8, 2),
(1019, 123, 87, 8, 2),
(1020, 123, 88, 8, 2),
(1021, 123, 89, 8, 2),
(1022, 123, 90, 8, 2),
(1023, 123, 91, 8, 2),
(1024, 123, 92, 8, 2),
(1025, 123, 93, 8, 2),
(1026, 123, 94, 8, 2),
(1027, 123, 95, 8, 2),
(1028, 123, 96, 8, 2),
(1029, 123, 97, 8, 2),
(1030, 123, 98, 8, 2),
(1031, 123, 99, 8, 2),
(1032, 123, 100, 8, 2),
(1033, 123, 101, 8, 2),
(1034, 123, 102, 8, 2),
(1035, 123, 103, 8, 2),
(1036, 123, 104, 8, 2),
(1037, 123, 105, 8, 2),
(1038, 123, 106, 8, 2),
(1039, 123, 107, 8, 2),
(1040, 123, 108, 8, 2),
(1041, 123, 109, 8, 2),
(1042, 123, 110, 8, 2),
(1043, 123, 111, 8, 2),
(1044, 123, 112, 8, 2),
(1045, 123, 113, 8, 2),
(1046, 123, 114, 8, 2),
(1047, 123, 115, 8, 2),
(1048, 123, 116, 8, 2),
(1049, 123, 117, 8, 2),
(1050, 123, 118, 8, 2),
(1051, 123, 119, 8, 2),
(1052, 123, 120, 8, 2),
(1053, 123, 121, 8, 2),
(1054, 123, 122, 8, 2),
(1055, 123, 123, 8, 2),
(1056, 123, 124, 8, 2),
(1057, 123, 125, 8, 2),
(1058, 123, 126, 8, 2),
(1059, 123, 127, 8, 2),
(1060, 123, 128, 8, 2),
(1061, 123, 129, 8, 2),
(1062, 123, 130, 8, 2),
(1063, 123, 131, 8, 2),
(1064, 123, 132, 8, 2),
(1065, 123, 133, 8, 2),
(1066, 123, 134, 8, 2),
(1067, 123, 135, 8, 2),
(1068, 123, 136, 8, 2),
(1069, 123, 137, 8, 2),
(1070, 123, 138, 8, 2),
(1071, 123, 139, 8, 2),
(1072, 123, 140, 8, 2),
(1073, 123, 141, 8, 2),
(1074, 123, 142, 8, 2),
(1075, 123, 143, 8, 2),
(1076, 123, 144, 8, 2),
(1077, 123, 145, 8, 2),
(1078, 123, 146, 8, 2),
(1079, 123, 147, 8, 2),
(1080, 123, 148, 8, 2),
(1081, 123, 149, 8, 2),
(1082, 123, 150, 8, 2),
(1083, 123, 151, 8, 2),
(1084, 123, 152, 8, 2),
(1085, 123, 153, 8, 2),
(1086, 123, 154, 8, 2),
(1087, 123, 155, 8, 2),
(1088, 123, 156, 8, 2),
(1089, 123, 157, 8, 2),
(1090, 123, 158, 8, 2),
(1091, 123, 159, 8, 2),
(1092, 123, 160, 8, 2),
(1093, 123, 161, 8, 2),
(1094, 123, 162, 8, 2),
(1095, 123, 163, 8, 2),
(1096, 123, 164, 8, 2),
(1097, 123, 165, 8, 2),
(1098, 123, 166, 8, 2),
(1099, 123, 167, 8, 2),
(1100, 123, 168, 8, 2),
(1101, 123, 169, 8, 2),
(1102, 123, 170, 8, 2),
(1103, 123, 171, 8, 2),
(1104, 123, 172, 8, 2),
(1105, 123, 173, 8, 2),
(1106, 123, 174, 8, 2),
(1107, 123, 175, 8, 2),
(1108, 123, 176, 8, 2),
(1109, 123, 177, 8, 2),
(1110, 123, 178, 8, 2),
(1111, 123, 179, 8, 2),
(1112, 123, 180, 8, 2),
(1113, 123, 181, 8, 2),
(1114, 123, 182, 8, 2),
(1115, 123, 183, 8, 2),
(1116, 123, 184, 8, 2),
(1117, 123, 185, 8, 2),
(1118, 123, 186, 8, 2),
(1119, 123, 187, 8, 2),
(1120, 123, 188, 8, 2),
(1121, 123, 189, 8, 2),
(1122, 123, 190, 8, 2),
(1123, 123, 191, 8, 2),
(1124, 123, 192, 8, 2),
(1125, 123, 193, 8, 2),
(1126, 123, 194, 8, 2),
(1127, 123, 195, 8, 2),
(1128, 123, 196, 8, 2),
(1129, 123, 197, 8, 2),
(1130, 123, 198, 8, 2),
(1131, 123, 199, 8, 2),
(1132, 123, 200, 8, 2),
(1133, 123, 201, 8, 2),
(1134, 123, 202, 8, 2),
(1135, 123, 203, 8, 2),
(1136, 123, 204, 8, 2),
(1137, 123, 205, 8, 2),
(1138, 123, 206, 8, 2),
(1139, 123, 207, 8, 2),
(1140, 123, 208, 8, 2),
(1141, 123, 209, 8, 2),
(1142, 123, 210, 8, 2),
(1143, 123, 211, 8, 2),
(1144, 123, 212, 8, 2),
(1145, 123, 213, 8, 2),
(1146, 123, 214, 8, 2),
(1147, 123, 215, 8, 2),
(1148, 123, 216, 8, 2),
(1149, 123, 217, 8, 2),
(1150, 123, 218, 8, 2),
(1151, 123, 219, 8, 2),
(1152, 123, 220, 8, 2),
(1153, 123, 221, 8, 2),
(1154, 123, 222, 8, 2),
(1155, 123, 223, 8, 2),
(1156, 123, 224, 8, 2),
(1157, 123, 225, 8, 2),
(1158, 123, 226, 8, 2),
(1159, 123, 227, 8, 2),
(1160, 123, 228, 8, 2),
(1161, 123, 229, 8, 2),
(1162, 123, 230, 8, 2),
(1163, 123, 231, 8, 2),
(1164, 123, 232, 8, 2),
(1165, 123, 233, 8, 2),
(1166, 123, 234, 8, 2),
(1167, 123, 235, 8, 2),
(1168, 123, 236, 8, 2),
(1169, 123, 237, 8, 2),
(1170, 123, 238, 8, 2),
(1171, 123, 239, 8, 2),
(1172, 123, 240, 8, 2),
(1173, 123, 241, 8, 2),
(1174, 123, 242, 8, 2),
(1175, 123, 243, 8, 2),
(1176, 123, 244, 8, 2),
(1177, 123, 245, 8, 2),
(1178, 123, 246, 8, 2),
(1179, 123, 247, 8, 2),
(1180, 123, 248, 8, 2),
(1181, 123, 249, 8, 2),
(1182, 123, 250, 8, 2),
(1183, 123, 251, 8, 2),
(1184, 123, 252, 8, 2),
(1185, 123, 253, 8, 2),
(1186, 123, 254, 8, 2),
(1187, 123, 255, 8, 2),
(1188, 123, 256, 8, 2),
(1189, 123, 257, 8, 2),
(1190, 123, 258, 8, 2),
(1191, 123, 259, 8, 2),
(1192, 123, 260, 8, 2),
(1193, 123, 261, 8, 2),
(1194, 123, 262, 8, 2),
(1195, 123, 263, 8, 2),
(1196, 123, 264, 8, 2),
(1197, 123, 265, 8, 2),
(1198, 123, 266, 8, 2),
(1199, 123, 267, 8, 2),
(1200, 123, 268, 8, 2),
(1201, 123, 269, 8, 2),
(1202, 123, 270, 8, 2),
(1203, 123, 271, 8, 2),
(1204, 123, 272, 8, 2),
(1205, 123, 273, 8, 2),
(1206, 123, 274, 8, 2),
(1207, 123, 275, 8, 2),
(1208, 123, 276, 8, 2),
(1209, 123, 277, 8, 2),
(1210, 123, 278, 8, 2),
(1211, 123, 279, 8, 2),
(1212, 123, 280, 8, 2),
(1213, 123, 281, 8, 2),
(1214, 123, 282, 8, 2),
(1215, 123, 283, 8, 2),
(1216, 123, 284, 8, 2),
(1217, 123, 285, 8, 2),
(1218, 123, 286, 8, 2),
(1219, 123, 287, 8, 2),
(1220, 123, 288, 8, 2),
(1221, 123, 289, 8, 2),
(1222, 123, 290, 8, 2),
(1223, 123, 291, 8, 2),
(1224, 123, 292, 8, 2),
(1225, 123, 293, 8, 2),
(1226, 123, 294, 8, 2),
(1227, 123, 295, 8, 2),
(1228, 123, 296, 8, 2),
(1229, 123, 297, 8, 2),
(1230, 123, 298, 8, 2),
(1231, 123, 299, 8, 2),
(1232, 123, 300, 8, 2),
(1233, 123, 301, 8, 2),
(1234, 123, 302, 8, 2),
(1235, 123, 303, 8, 2),
(1236, 123, 304, 8, 2),
(1237, 123, 305, 8, 2),
(1238, 123, 306, 8, 2),
(1239, 123, 307, 8, 2),
(1240, 123, 308, 8, 2),
(1241, 123, 309, 8, 2),
(1242, 123, 310, 8, 2),
(1243, 123, 311, 8, 2),
(1244, 123, 312, 8, 2),
(1245, 123, 313, 8, 2),
(1246, 123, 314, 8, 2),
(1247, 123, 315, 8, 2),
(1248, 123, 316, 8, 2),
(1249, 123, 317, 8, 2),
(1250, 123, 318, 8, 2),
(1251, 123, 319, 8, 2),
(1252, 123, 320, 8, 2),
(1253, 123, 321, 8, 2),
(1254, 123, 322, 8, 2),
(1255, 123, 323, 8, 2),
(1256, 123, 324, 8, 2),
(1257, 123, 325, 8, 2),
(1258, 123, 326, 8, 2),
(1259, 123, 327, 8, 2),
(1260, 123, 328, 8, 2),
(1261, 123, 329, 8, 2),
(1262, 123, 330, 8, 2),
(1263, 123, 331, 8, 2),
(1264, 123, 332, 8, 2),
(1265, 123, 333, 8, 2),
(1266, 123, 334, 8, 2),
(1267, 123, 335, 8, 2),
(1268, 123, 336, 8, 2),
(1269, 123, 337, 8, 2),
(1270, 123, 338, 8, 2),
(1271, 123, 339, 8, 2),
(1272, 123, 340, 8, 2),
(1273, 123, 341, 8, 2),
(1274, 123, 342, 8, 2),
(1275, 123, 343, 8, 2),
(1276, 123, 344, 8, 2),
(1277, 123, 345, 8, 2),
(1278, 123, 346, 8, 2),
(1279, 123, 347, 8, 2),
(1280, 123, 348, 8, 2),
(1281, 123, 349, 8, 2),
(1282, 123, 350, 8, 2),
(1283, 123, 351, 8, 2),
(1284, 123, 352, 8, 2),
(1285, 123, 353, 8, 2),
(1286, 123, 354, 8, 2),
(1287, 123, 355, 8, 2),
(1288, 123, 356, 8, 2),
(1289, 123, 357, 8, 2),
(1290, 123, 358, 8, 2),
(1291, 123, 359, 8, 2),
(1292, 123, 360, 8, 2),
(1293, 123, 361, 8, 2),
(1294, 123, 362, 8, 2),
(1295, 123, 363, 8, 2),
(1296, 123, 364, 8, 2),
(1297, 123, 365, 8, 2),
(1298, 123, 366, 8, 2),
(1299, 123, 367, 8, 2),
(1300, 123, 368, 8, 2),
(1301, 123, 369, 8, 2),
(1302, 123, 370, 8, 2),
(1303, 123, 371, 8, 2),
(1304, 123, 372, 8, 2),
(1305, 123, 373, 8, 2),
(1306, 123, 374, 8, 2),
(1307, 123, 375, 8, 2),
(1308, 123, 376, 8, 2),
(1309, 123, 377, 8, 2),
(1310, 123, 378, 8, 2),
(1311, 123, 379, 8, 2),
(1312, 123, 380, 8, 2),
(1313, 123, 381, 8, 2),
(1314, 123, 382, 8, 2),
(1315, 123, 383, 8, 2),
(1316, 123, 384, 8, 2),
(1317, 123, 385, 8, 2),
(1318, 123, 386, 8, 2),
(1319, 123, 387, 8, 2),
(1320, 123, 388, 8, 2),
(1321, 123, 389, 8, 2),
(1322, 123, 390, 8, 2),
(1323, 123, 391, 8, 2),
(1324, 123, 392, 8, 2),
(1325, 123, 393, 8, 2),
(1326, 123, 394, 8, 2),
(1327, 123, 395, 8, 2),
(1328, 123, 396, 8, 2),
(1329, 123, 397, 8, 2),
(1330, 123, 398, 8, 2),
(1331, 123, 399, 8, 2),
(1332, 123, 0, 3, 3),
(1333, 123, 1, 3, 3),
(1334, 123, 2, 3, 3),
(1335, 123, 3, 3, 3),
(1336, 123, 4, 3, 3),
(1337, 123, 5, 3, 3),
(1338, 123, 6, 3, 3),
(1339, 123, 7, 3, 3),
(1340, 123, 8, 3, 3),
(1341, 123, 9, 3, 3),
(1342, 123, 10, 3, 3),
(1343, 123, 11, 3, 3),
(1344, 123, 12, 3, 3),
(1345, 123, 13, 3, 3),
(1346, 123, 14, 3, 3),
(1347, 123, 15, 3, 3),
(1348, 123, 16, 3, 3),
(1349, 123, 17, 3, 3),
(1350, 123, 18, 3, 3),
(1351, 123, 19, 3, 3),
(1352, 123, 20, 3, 3),
(1353, 123, 21, 3, 3),
(1354, 123, 22, 3, 3),
(1355, 123, 23, 3, 3),
(1356, 123, 24, 3, 3),
(1357, 123, 25, 3, 3),
(1358, 123, 26, 3, 3),
(1359, 123, 27, 3, 3),
(1360, 123, 28, 3, 3),
(1361, 123, 29, 3, 3),
(1362, 123, 30, 3, 3),
(1363, 123, 31, 3, 3),
(1364, 123, 32, 3, 3),
(1365, 123, 33, 3, 3),
(1366, 123, 34, 3, 3),
(1367, 123, 35, 3, 3),
(1368, 123, 36, 3, 3),
(1369, 123, 37, 3, 3),
(1370, 123, 38, 3, 3),
(1371, 123, 39, 3, 3),
(1372, 123, 40, 3, 3),
(1373, 123, 41, 3, 3),
(1374, 123, 42, 3, 3),
(1375, 123, 43, 3, 3),
(1376, 123, 44, 3, 3),
(1377, 123, 45, 3, 3),
(1378, 123, 46, 3, 3),
(1379, 123, 47, 3, 3),
(1380, 123, 48, 3, 3),
(1381, 123, 49, 3, 3),
(1382, 123, 50, 3, 3),
(1383, 123, 51, 3, 3),
(1384, 123, 52, 3, 3),
(1385, 123, 53, 3, 3),
(1386, 123, 54, 3, 3),
(1387, 123, 55, 3, 3),
(1388, 123, 56, 3, 3),
(1389, 123, 57, 3, 3),
(1390, 123, 58, 3, 3),
(1391, 123, 59, 3, 3),
(1392, 123, 60, 3, 3),
(1393, 123, 61, 3, 3),
(1394, 123, 62, 3, 3),
(1395, 123, 63, 3, 3),
(1396, 123, 64, 3, 3),
(1397, 123, 65, 3, 3),
(1398, 123, 66, 3, 3),
(1399, 123, 67, 3, 3),
(1400, 123, 68, 3, 3),
(1401, 123, 69, 3, 3),
(1402, 123, 70, 3, 3),
(1403, 123, 71, 3, 3),
(1404, 123, 72, 3, 3),
(1405, 123, 73, 3, 3),
(1406, 123, 74, 3, 3),
(1407, 123, 75, 3, 3),
(1408, 123, 76, 3, 3),
(1409, 123, 77, 3, 3),
(1410, 123, 78, 3, 3),
(1411, 123, 79, 3, 3),
(1412, 123, 80, 3, 3),
(1413, 123, 81, 3, 3),
(1414, 123, 82, 3, 3),
(1415, 123, 83, 3, 3),
(1416, 123, 84, 3, 3),
(1417, 123, 85, 3, 3),
(1418, 123, 86, 3, 3),
(1419, 123, 87, 3, 3),
(1420, 123, 88, 3, 3),
(1421, 123, 89, 3, 3),
(1422, 123, 90, 3, 3),
(1423, 123, 91, 3, 3),
(1424, 123, 92, 3, 3),
(1425, 123, 93, 3, 3),
(1426, 123, 94, 3, 3),
(1427, 123, 95, 3, 3),
(1428, 123, 96, 3, 3),
(1429, 123, 97, 3, 3),
(1430, 123, 98, 3, 3),
(1431, 123, 99, 3, 3),
(1432, 123, 100, 3, 3),
(1433, 123, 101, 3, 3),
(1434, 123, 102, 3, 3),
(1435, 123, 103, 3, 3),
(1436, 123, 104, 3, 3),
(1437, 123, 105, 3, 3),
(1438, 123, 106, 3, 3),
(1439, 123, 107, 3, 3),
(1440, 123, 108, 3, 3),
(1441, 123, 109, 3, 3),
(1442, 123, 110, 3, 3),
(1443, 123, 111, 3, 3),
(1444, 123, 112, 3, 3),
(1445, 123, 113, 3, 3),
(1446, 123, 114, 3, 3),
(1447, 123, 115, 3, 3),
(1448, 123, 116, 3, 3),
(1449, 123, 117, 3, 3),
(1450, 123, 118, 3, 3),
(1451, 123, 119, 3, 3),
(1452, 123, 120, 3, 3),
(1453, 123, 121, 3, 3),
(1554, 1, 0, 4, 1),
(1555, 1, 1, 4, 1),
(1556, 1, 2, 4, 1),
(1557, 1, 3, 4, 1),
(1558, 1, 4, 4, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `RoomType`
--

CREATE TABLE `RoomType` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `type` varchar(500) NOT NULL,
  `description` varchar(500) NOT NULL,
  `pricePerBed` int(11) NOT NULL,
  `principal` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `RoomType`
--

INSERT INTO `RoomType` (`id`, `hotel_id`, `type`, `description`, `pricePerBed`, `principal`) VALUES
(0, 123, 'Klasik', 'mjdjksffkjfbds', 49, 0),
(0, 124, 'Basic', 'room', 20, 0),
(1, 1, 'Presidential', 'Cozy af', 20, 0),
(2, 123, 'TOP', 'better room overal', 99, 0),
(3, 123, 'jeje', 'des', 33, 0);

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
(3, 'Paid'),
(4, 'Confirmed'),
(5, 'Canceled'),
(6, 'Awaiting refund'),
(7, 'Finished');

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
  `employed` int(11) NOT NULL DEFAULT '0',
  `password` varchar(128) NOT NULL,
  `rights` int(11) NOT NULL DEFAULT '4',
  `address` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `User`
--

INSERT INTO `User` (`id`, `name`, `surname`, `phone_number`, `email`, `employed`, `password`, `rights`, `address`) VALUES
(1, 'Admin', 'Adminer', '', 'admin@planaxis.space', 0, '$2y$12$X2WmszHaybXVpUPSgM6b3ea6BKus4AIv71R6Kun1xVPGfWddbnsq.', 1, ''),
(3, 'Jakub', 'Sekula', '123456', 'sekula@planaxis.space', 0, '$2y$10$udPopy.pCI2t1anbhTDPpOoconGtDCOVTQ/pbbM9TOf7BYq9ySFn.', 2, ''),
(4, 'Martin', 'Fekete', '1234532', 'fekete@planaxis.space', 1, '$2y$10$r2dThspR99AcZArGVRFAKOy58cvbuq3AM4AiXTot2PDJBXdmDkUqW', 3, ''),
(29, 'testuser', 'srname', '1393922222', 'porn@planaxis.space', 0, '$2y$10$PO9rO8YAfjiIj/r9vRT0o.4irIcYpDVwIQpCrkTc5ntTprhesVlMS', 0, ''),
(38, 'Lukas', 'Perina', NULL, 'perina@planaxis.space', 1, '$2y$10$zQNAoAcNJYKBF7WqGcLSWu1Hc0R5HFzY6TknOBzmO4GEv2.FmThoO', 3, NULL),
(39, 'Jeje', 'surname', '3828282', 'user@planaxis.space', 78, '$2y$10$XJZPFhcNiLPn8/2m2ZZ/iexMZqIqCfjAzHefMLb1eKBr133NxrZYq', 3, NULL);

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `Equipment`
--
ALTER TABLE `Equipment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `EquipmentHotel` (`hotel_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Klíče pro tabulku `Hotel`
--
ALTER TABLE `Hotel`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `Owns`
--
ALTER TABLE `Owns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Owns_ibfk_1` (`idowner`),
  ADD KEY `Owns_ibfk_2` (`idhotel`);

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
  ADD KEY `ReservationUser` (`user_id`),
  ADD KEY `hotel_id` (`hotel_id`),
  ADD KEY `Reservation_ibfk_3` (`stav`),
  ADD KEY `Reservation_ibfk_2` (`room_id`);

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
  ADD KEY `Room_ibfk_2` (`type`);

--
-- Klíče pro tabulku `RoomType`
--
ALTER TABLE `RoomType`
  ADD PRIMARY KEY (`id`,`hotel_id`);

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
  ADD KEY `UserEmployed` (`employed`),
  ADD KEY `User_ibfk_1` (`rights`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `Equipment`
--
ALTER TABLE `Equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `Hotel`
--
ALTER TABLE `Hotel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT pro tabulku `Owns`
--
ALTER TABLE `Owns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pro tabulku `Photos`
--
ALTER TABLE `Photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT pro tabulku `Reservation`
--
ALTER TABLE `Reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pro tabulku `Reviews`
--
ALTER TABLE `Reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pro tabulku `Rights`
--
ALTER TABLE `Rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pro tabulku `Room`
--
ALTER TABLE `Room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1559;

--
-- AUTO_INCREMENT pro tabulku `State`
--
ALTER TABLE `State`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pro tabulku `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `Equipment`
--
ALTER TABLE `Equipment`
  ADD CONSTRAINT `EquipmentHotel` FOREIGN KEY (`hotel_id`) REFERENCES `Hotel` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Equipment_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `Room` (`id`);

--
-- Omezení pro tabulku `Owns`
--
ALTER TABLE `Owns`
  ADD CONSTRAINT `Owns_ibfk_1` FOREIGN KEY (`idowner`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Owns_ibfk_2` FOREIGN KEY (`idhotel`) REFERENCES `Hotel` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `ReservationUser` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`),
  ADD CONSTRAINT `Reservation_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `Hotel` (`id`),
  ADD CONSTRAINT `Reservation_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `Room` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Reservation_ibfk_3` FOREIGN KEY (`stav`) REFERENCES `State` (`id`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `Room_ibfk_2` FOREIGN KEY (`type`) REFERENCES `RoomType` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `User`
--
ALTER TABLE `User`
  ADD CONSTRAINT `UserEmployed` FOREIGN KEY (`employed`) REFERENCES `Hotel` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `User_ibfk_1` FOREIGN KEY (`rights`) REFERENCES `Rights` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
