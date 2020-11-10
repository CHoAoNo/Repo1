-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 10 2020 г., 16:23
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
-- Структура таблицы `a_category`
--

DROP TABLE IF EXISTS `a_category`;
CREATE TABLE IF NOT EXISTS `a_category` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` int(10) UNSIGNED NOT NULL,
  `name` varchar(40) NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `a_category`
--

INSERT INTO `a_category` (`id`, `code`, `name`, `parent_id`) VALUES
(1, 1, 'Электротовары', 0),
(2, 2, 'Кухонная посуда', 0),
(3, 1, 'Чайники', 1),
(4, 2124, 'Сковородки', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `a_ price`
--

DROP TABLE IF EXISTS `a_ price`;
CREATE TABLE IF NOT EXISTS `a_ price` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) UNSIGNED NOT NULL,
  `type_price` varchar(20) NOT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `a_ price`
--

INSERT INTO `a_ price` (`id`, `product_id`, `type_price`, `price`) VALUES
(1, 1, 'sale', 150),
(2, 2, '', 400);

-- --------------------------------------------------------

--
-- Структура таблицы `a_product`
--

DROP TABLE IF EXISTS `a_product`;
CREATE TABLE IF NOT EXISTS `a_product` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` int(10) UNSIGNED NOT NULL,
  `name` varchar(70) NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `a_product`
--

INSERT INTO `a_product` (`id`, `code`, `name`, `category_id`) VALUES
(1, 1, 'чайник', 3),
(2, 2, 'сковородка', 4),
(3, 1, 'чайник', 3),
(4, 1, 'чайник', 3),
(5, 2, 'сковорода', 4),
(6, 1, 'чайник', 3),
(7, 2, 'сковорода', 4),
(8, 3, 'утюг', 1),
(9, 4, 'тарелка', 2),
(10, 3, 'утюг', 1),
(11, 4, 'тарелка', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `a_property`
--

DROP TABLE IF EXISTS `a_property`;
CREATE TABLE IF NOT EXISTS `a_property` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) UNSIGNED NOT NULL,
  `property` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `a_property`
--

INSERT INTO `a_property` (`id`, `product_id`, `property`) VALUES
(1, 1, 'Цвет черный'),
(2, 2, 'Цвет синий'),
(3, 3, 'Цвет белый');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `a_ price`
--
ALTER TABLE `a_ price`
  ADD CONSTRAINT `a_ price_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `a_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `a_product`
--
ALTER TABLE `a_product`
  ADD CONSTRAINT `a_product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `a_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `a_property`
--
ALTER TABLE `a_property`
  ADD CONSTRAINT `a_property_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `a_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
