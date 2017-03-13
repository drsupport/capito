-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Мар 12 2017 г., 23:25
-- Версия сервера: 5.5.54-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `capito`
--

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_affiliates`
--

CREATE TABLE IF NOT EXISTS `tbl_affiliates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `site` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_landings`
--

CREATE TABLE IF NOT EXISTS `tbl_landings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `segment` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_logs`
--

CREATE TABLE IF NOT EXISTS `tbl_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `query` text CHARACTER SET utf8 NOT NULL,
  `response` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `tbl_logs`
--

INSERT INTO `tbl_logs` (`id`, `datetime`, `status`, `query`, `response`) VALUES
(1, '2017-03-12 21:26:10', 1, './vendor/ariya/phantomjs/bin/phantomjs yws.js пиджеум 1 2>&1', ''),
(2, '2017-03-12 21:26:50', 1, './vendor/ariya/phantomjs/bin/phantomjs yws.js pygeum 2 2>&1', ''),
(3, '2017-03-12 22:59:57', 1, './vendor/ariya/phantomjs/bin/phantomjs yws.js сементал 3 2>&1', ''),
(4, '2017-03-12 23:23:46', 1, './vendor/ariya/phantomjs/bin/phantomjs yws.js пиджеум 4 2>&1', '');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_products`
--

CREATE TABLE IF NOT EXISTS `tbl_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `tbl_products`
--

INSERT INTO `tbl_products` (`id`, `name`) VALUES
(2, 'Пиджеум'),
(3, 'Сементал');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_products_landings`
--

CREATE TABLE IF NOT EXISTS `tbl_products_landings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` int(11) NOT NULL,
  `landing` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_products_segments`
--

CREATE TABLE IF NOT EXISTS `tbl_products_segments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` int(11) NOT NULL,
  `segment` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_products_words`
--

CREATE TABLE IF NOT EXISTS `tbl_products_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` int(11) NOT NULL,
  `word` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `tbl_products_words`
--

INSERT INTO `tbl_products_words` (`id`, `product`, `word`) VALUES
(1, 2, 2),
(2, 2, 3),
(3, 3, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_segments`
--

CREATE TABLE IF NOT EXISTS `tbl_segments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_stats`
--

CREATE TABLE IF NOT EXISTS `tbl_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `word` int(11) DEFAULT NULL,
  `device` int(11) DEFAULT NULL,
  `geo` int(11) DEFAULT NULL,
  `impressions` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `tbl_stats`
--

INSERT INTO `tbl_stats` (`id`, `datetime`, `word`, `device`, `geo`, `impressions`) VALUES
(1, '2017-03-12 00:00:00', 2, NULL, NULL, 5088),
(2, '2017-03-12 00:00:00', 3, NULL, NULL, 125),
(3, '2017-03-12 00:00:00', 4, NULL, NULL, 29584),
(4, '2017-03-12 00:00:00', 2, NULL, NULL, 5088);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(30) NOT NULL,
  `user_password` varchar(32) NOT NULL,
  `user_hash` varchar(32) NOT NULL,
  `user_ip` varchar(15) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001 ;

--
-- Дамп данных таблицы `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `user_login`, `user_password`, `user_hash`, `user_ip`) VALUES
(1000, 'buryachan@gmail.com', '14e1b600b1fd579f47433b88e8d85291', 'ec5a7f961b1c59abfcd19770ef705ea8', '89.175.11.138');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_words`
--

CREATE TABLE IF NOT EXISTS `tbl_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `tbl_words`
--

INSERT INTO `tbl_words` (`id`, `name`) VALUES
(2, 'пиджеум'),
(3, 'pygeum'),
(4, 'сементал');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
