-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 12 2020 г., 00:06
-- Версия сервера: 5.7.32
-- Версия PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test_samson`
--

-- --------------------------------------------------------

--
-- Структура таблицы `a_product`
--

DROP TABLE IF EXISTS `a_product`;
CREATE TABLE IF NOT EXISTS `a_product` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` int(10) UNSIGNED NOT NULL,
  `name` varchar(70) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `a_product`
--

INSERT INTO `a_product` (`id`, `code`, `name`) VALUES
(1, 201, 'Бумага А4'),
(2, 202, 'Бумага А3'),
(3, 302, 'Принтер Canon'),
(4, 305, 'Принтер HP'),
(5, 201, 'Товар5'),
(6, 202, 'Товар6'),
(7, 302, 'Товар7'),
(8, 305, 'Товар8');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
