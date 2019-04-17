-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 11-04-2019 a las 03:17:20
-- Versión del servidor: 5.7.23
-- Versión de PHP: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `contreras`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

DROP TABLE IF EXISTS `equipo`;
CREATE TABLE IF NOT EXISTS `equipo` (
  `equipo_id` int(15) NOT NULL AUTO_INCREMENT,
  `equipo_name` varchar(200) NOT NULL,
  `equipo_logo` varchar(200) DEFAULT NULL,
  `equipo_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`equipo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `equipo`
--

INSERT INTO `equipo` (`equipo_id`, `equipo_name`, `equipo_logo`, `equipo_status`) VALUES
(1, 'Argentinos jr', 'argentinos2.png', 1),
(11, 'manchester', 'manchester.png', 1),
(10, 'janner c', 'chivas.png', 1),
(9, 'Holanda', 'holanda.png', 1),
(13, 'America', 'america.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugador`
--

DROP TABLE IF EXISTS `jugador`;
CREATE TABLE IF NOT EXISTS `jugador` (
  `jugador_id` int(200) NOT NULL AUTO_INCREMENT,
  `equipo_id` int(15) NOT NULL,
  `jugador_name` varchar(250) NOT NULL,
  `jugador_lastname_pat` varchar(200) NOT NULL,
  `jugador_lastname_mat` varchar(200) NOT NULL,
  `jugador_number` int(10) NOT NULL,
  `jugador_picture` varchar(250) DEFAULT NULL,
  `jugador_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`jugador_id`),
  KEY `equipo_id` (`equipo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `jugador`
--

INSERT INTO `jugador` (`jugador_id`, `equipo_id`, `jugador_name`, `jugador_lastname_pat`, `jugador_lastname_mat`, `jugador_number`, `jugador_picture`, `jugador_status`) VALUES
(1, 1, 'Diego Daniel', 'Chávez', 'Luque', 8, 'diego.png', 1),
(7, 9, 'Fernando', 'Chávez ', 'Luque', 15, 'fernando.jpg', 1),
(8, 10, 'otoniel', 'rojasq', 'rojas', 10, 'OTONIEL.JPG', 1),
(17, 11, 'Paul', 'Labile', 'Pogba', 8, 'pogba.jpg', 1),
(14, 1, 'julio', 'corona', 'trejo', 26, 'J-0KkbBw.png', 1),
(18, 13, 'oribe', 'peralta', 'peralta', 11, 'oribe.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidos`
--

DROP TABLE IF EXISTS `partidos`;
CREATE TABLE IF NOT EXISTS `partidos` (
  `partido_id` int(250) NOT NULL AUTO_INCREMENT,
  `equipo_id_1` int(200) NOT NULL,
  `equipo_id_2` int(200) NOT NULL,
  `partido_marcador` varchar(100) NOT NULL,
  `partido_jornada` int(50) NOT NULL,
  `partido_campo` varchar(200) NOT NULL,
  `partido_status` int(11) NOT NULL,
  PRIMARY KEY (`partido_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `partidos`
--

INSERT INTO `partidos` (`partido_id`, `equipo_id_1`, `equipo_id_2`, `partido_marcador`, `partido_jornada`, `partido_campo`, `partido_status`) VALUES
(1, 1, 10, '2-0', 1, 'Contreras', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `name`, `description`, `price`) VALUES
(1, 'Hola', 'fdasfsa', 'fdasdf'),
(2, 'fasdf', 'fasdfas', 'fdasfsa'),
(3, 'Bq AQUARIS', 'Movil BQ con Android', '140'),
(57, 'PS32', 'FASDFA', '32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `alternative` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `name`, `surname`, `description`, `email`, `password`, `image`, `alternative`) VALUES
(19, 'Diego', 'chavez', 'Admin', 'd13_luque@hotmail.com', '$2y$06$Y3Vyc29femVuZF9mcmFtZOwKftjw0CIukcnafFjOJNOnng/5LG0i6', 'null', 'null');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
