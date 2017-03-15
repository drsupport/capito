-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Мар 14 2017 г., 17:21
-- Версия сервера: 5.5.50-0ubuntu0.14.04.1-log
-- Версия PHP: 5.5.9-1ubuntu4.19

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Дамп данных таблицы `tbl_logs`
--

INSERT INTO `tbl_logs` (`id`, `datetime`, `status`, `query`, `response`) VALUES
(20, '2017-03-14 14:48:27', 1, './vendor/ariya/phantomjs/bin/phantomjs yws.js пиджеум 20 ivanov.vladimir.v sp@rt@nec 2>&1', ''),
(21, '2017-03-14 17:20:29', 1, './vendor/ariya/phantomjs/bin/phantomjs yws.js yarsagumba 21 ivanov.vladimir.v sp@rt@nec 2>&1', ''),
(22, '2017-03-14 17:20:48', 1, './vendor/ariya/phantomjs/bin/phantomjs yws.js ярсагумба 22 ivanov.vladimir.v sp@rt@nec 2>&1', '');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_products`
--

CREATE TABLE IF NOT EXISTS `tbl_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Дамп данных таблицы `tbl_products`
--

INSERT INTO `tbl_products` (`id`, `name`) VALUES
(2, 'Пиджеум'),
(3, 'Сементал'),
(29, 'Ярсагумба');

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
  UNIQUE KEY `once` (`product`,`word`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Дамп данных таблицы `tbl_products_words`
--

INSERT INTO `tbl_products_words` (`id`, `product`, `word`) VALUES
(1, 2, 2),
(2, 2, 3),
(3, 3, 4),
(32, 29, 31),
(33, 29, 32);

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
-- Структура таблицы `tbl_settings`
--

CREATE TABLE IF NOT EXISTS `tbl_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(30) CHARACTER SET utf8 NOT NULL,
  `value1` varchar(32) CHARACTER SET utf8 NOT NULL,
  `value2` varchar(32) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `key`, `value1`, `value2`) VALUES
(1, 'yws', 'ivanov.vladimir.v', 'sp@rt@nec');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_stats`
--

CREATE TABLE IF NOT EXISTS `tbl_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` date NOT NULL,
  `word` int(11) DEFAULT NULL,
  `device` int(11) DEFAULT NULL,
  `geo` int(11) DEFAULT NULL,
  `impressions` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `once` (`datetime`,`word`,`impressions`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Дамп данных таблицы `tbl_stats`
--

INSERT INTO `tbl_stats` (`id`, `datetime`, `word`, `device`, `geo`, `impressions`) VALUES
(1, '2017-03-12', 2, NULL, NULL, 5088),
(2, '2017-03-12', 3, NULL, NULL, 125),
(3, '2017-03-12', 4, NULL, NULL, 29584),
(17, '2017-03-13', 2, NULL, NULL, 5019),
(18, '2017-03-14', 2, NULL, NULL, 4953),
(19, '2017-03-14', 3, NULL, NULL, 116),
(20, '2017-03-14', 4, NULL, NULL, 28292),
(23, '2017-03-13', 3, NULL, NULL, 125),
(24, '2017-03-13', 4, NULL, NULL, 29584),
(39, '2017-03-14', 32, NULL, NULL, 159),
(40, '2017-03-14', 31, NULL, NULL, 12725);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_stats_products`
--

CREATE TABLE IF NOT EXISTS `tbl_stats_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` date NOT NULL,
  `word` int(11) DEFAULT NULL,
  `impressions` int(11) DEFAULT NULL,
  `product` int(11) DEFAULT NULL,
  `words` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `once` (`datetime`,`product`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Дамп данных таблицы `tbl_stats_products`
--

INSERT INTO `tbl_stats_products` (`id`, `datetime`, `word`, `impressions`, `product`, `words`) VALUES
(1, '2017-03-14', 2, 5069, 2, 2),
(2, '2017-03-14', 1, 28292, 3, 1),
(5, '2017-03-13', 2, 5144, 2, 2),
(6, '2017-03-13', 1, 29584, 3, 1),
(11, '2017-03-12', 2, 5213, 2, 2),
(12, '2017-03-12', 1, 29584, 3, 1),
(21, '2017-03-14', 2, 12884, 29, 2);

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
(1000, 'buryachan@gmail.com', '14e1b600b1fd579f47433b88e8d85291', '63d94f811b677720faf498010ed2a58d', '89.175.11.138');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_words`
--

CREATE TABLE IF NOT EXISTS `tbl_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Дамп данных таблицы `tbl_words`
--

INSERT INTO `tbl_words` (`id`, `name`) VALUES
(3, 'pygeum'),
(32, 'yarsagumba'),
(2, 'пиджеум'),
(4, 'сементал'),
(31, 'ярсагумба');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
