-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Sam 15 Juin 2013 à 07:01
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `cloud`
--

-- --------------------------------------------------------

--
-- Structure de la table `shared`
--

CREATE TABLE IF NOT EXISTS `shared` (
  `uid` varchar(128) NOT NULL,
  `path` varchar(512) NOT NULL,
  `password` varchar(64) DEFAULT NULL,
  `count` int(12) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `path` (`path`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `shared`
--

INSERT INTO `shared` (`uid`, `path`, `password`, `count`, `created`) VALUES
('2785851bb6c2ad99831.53975192', 'Movies/Wildlife.wmv', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 1, '2013-06-14 07:16:58'),
('41951bb6c8a2e4856.71935131', 'Movies/Wildlife.wmv', '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', 0, '2013-06-14 07:18:34');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `email` varchar(320) NOT NULL,
  `password` varchar(42) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'admin', 'admin@mycloud.com', 'd033e22ae348aeb5660fc2140aec35850c4da997');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
