-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:8889
-- Üretim Zamanı: 17 May 2024, 07:02:44
-- Sunucu sürümü: 5.7.39
-- PHP Sürümü: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `test`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `products`
--

CREATE TABLE `products` (
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `stock` int(100) NOT NULL,
  `normal_price` decimal(6,2) NOT NULL,
  `discounted_price` decimal(6,2) NOT NULL,
  `expiration_date` date NOT NULL,
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `district` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `products`
--

INSERT INTO `products` (`title`, `stock`, `normal_price`, `discounted_price`, `expiration_date`, `image`, `city`, `district`) VALUES
('Brownie', 17, '10.00', '7.00', '2023-06-03', 'brownie.jpeg', 'Ankara', 'Umitkoy'),
('Cheese', 28, '15.00', '12.00', '2021-06-05', 'cheese.jpg', 'Istanbul', 'Besiktas'),
('Chips', 51, '17.75', '14.00', '2022-04-19', 'chips.jpg', 'Istanbul', 'Pendik'),
('Cracker', 33, '12.00', '11.25', '2017-09-30', 'cracker.jpg', 'Ankara', 'Cankaya'),
('Egg', 75, '40.00', '37.75', '2017-02-05', 'egg.jpg', 'Istanbul', 'Kadikoy'),
('Milk', 61, '25.00', '23.75', '2021-10-14', 'milk.jpeg', 'Istanbul', 'Levent'),
('Orange Juice', 24, '33.00', '30.00', '2021-04-04', 'orangeJuice.jpg', 'Ankara', 'Yenimahalle'),
('Salad', 31, '52.00', '45.00', '2021-08-09', 'salad.jpg', 'Ankara', 'Kecioren'),
('Toblerone', 24, '33.50', '30.00', '2020-12-03', 'toblerone.jpeg', 'Ankara', 'Mamak'),
('Yoghurt', 22, '30.00', '27.75', '2024-03-09', 'yoghurt.jpg', 'Istanbul', 'Besiktas'),
('Chicken', 40, '50.00', '45.00', '2022-07-10', 'chicken.jpg', 'Istanbul', 'Pendik'),
('Ground Beef', 30, '60.00', '45.00', '2021-08-08', 'groundBeef.jpg', 'Ankara', 'Umitkoy'),
('Noodles', 20, '10.00', '8.00', '2020-05-23', 'noodles.jpg', 'Ankara', 'Cankaya'),
('Sandwich', 39, '40.00', '35.00', '2025-11-26', 'sandwich.jpeg', 'Istanbul', 'Kadikoy'),
('Snickers', 38, '5.00', '3.00', '2023-05-21', 'snickers.jpg', 'Ankara', 'Kecioren'),
('Tortilla', 29, '15.00', '8.00', '2022-11-07', 'tortilla.jpg', 'Ankara', 'Cankaya'),
('Pizza', 10, '30.00', '25.00', '2020-05-31', 'pizza.jpg', 'Istanbul', 'Besiktas'),
('Fries', 22, '15.00', '10.00', '2022-06-07', 'fries.jpeg', 'Istanbul', 'Kadikoy');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
