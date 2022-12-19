-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 19 déc. 2022 à 09:47
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `systeme`
--

-- --------------------------------------------------------

--
-- Structure de la table `joueur`
--

DROP TABLE IF EXISTS `joueur`;
CREATE TABLE IF NOT EXISTS `joueur` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `IPs` text NOT NULL,
  `credits` int(11) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `etape_tuto` tinyint(1) NOT NULL DEFAULT '0',
  `creato` datetime NOT NULL,
  `lastco` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `joueur`
--

INSERT INTO `joueur` (`id`, `nom`, `email`, `IPs`, `credits`, `mdp`, `etape_tuto`, `creato`, `lastco`) VALUES
(3, 'Vartus', 'developpeurntic@gmail.com', '::1', 500, 'gPhvB3xN.mAVULqmxVcgCaqgkR0b7wdU.n1Grn0ekmnx35OJ30IDTnO/eAjAlGYx6/cJ/8avLHGmSIJAeMx.o0', 1, '2022-12-19 10:21:18', '2022-12-19 09:21:23');

-- --------------------------------------------------------

--
-- Structure de la table `perso`
--

DROP TABLE IF EXISTS `perso`;
CREATE TABLE IF NOT EXISTS `perso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_joueur` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `PV` int(11) NOT NULL COMMENT 'Vie',
  `FO` int(11) NOT NULL COMMENT 'FORCE',
  `diplomatie` int(11) NOT NULL,
  `sur_planete` int(11) NOT NULL,
  `fin_diplomatie` datetime DEFAULT NULL,
  `fin_voyage_planetaire` datetime DEFAULT NULL,
  `creato` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `perso`
--

INSERT INTO `perso` (`id`, `id_joueur`, `nom`, `PV`, `FO`, `diplomatie`, `sur_planete`, `fin_diplomatie`, `fin_voyage_planetaire`, `creato`) VALUES
(5, 3, 'Vartus', 1, 5, 4, 36, '2022-12-19 10:34:07', NULL, '2022-12-19 10:21:18');

-- --------------------------------------------------------

--
-- Structure de la table `planete`
--

DROP TABLE IF EXISTS `planete`;
CREATE TABLE IF NOT EXISTS `planete` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `perc_revolte` int(2) NOT NULL,
  `slot` int(2) NOT NULL,
  `gouverneur` int(11) NOT NULL,
  `id_systeme` bigint(20) UNSIGNED DEFAULT NULL,
  `nb_usine_vaiss_leger` int(11) NOT NULL,
  `nb_usine_vaiss_moyen` int(11) NOT NULL,
  `nb_usine_vaiss_lourd` int(11) NOT NULL,
  `nb_usine_ressource` int(11) NOT NULL,
  `nb_academie` int(11) NOT NULL,
  `creato` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `planete`
--

INSERT INTO `planete` (`id`, `nom`, `perc_revolte`, `slot`, `gouverneur`, `id_systeme`, `nb_usine_vaiss_leger`, `nb_usine_vaiss_moyen`, `nb_usine_vaiss_lourd`, `nb_usine_ressource`, `nb_academie`, `creato`) VALUES
(17, 'Ksi 1', 8, 8, 0, 8, 0, 0, 0, 0, 0, '2022-12-19 09:58:42'),
(18, 'Ksi 2', 23, 13, 0, 8, 0, 0, 0, 0, 0, '2022-12-19 09:58:42'),
(19, 'Ksi 3', 16, 3, 0, 8, 0, 0, 0, 0, 0, '2022-12-19 09:58:42'),
(20, 'Ksi 4', 14, 5, 0, 8, 0, 0, 0, 0, 0, '2022-12-19 09:58:42'),
(21, 'Ksi 5', 5, 9, 0, 8, 0, 0, 0, 0, 0, '2022-12-19 09:58:42'),
(22, 'Ksi 6', 21, 16, 0, 8, 0, 0, 0, 0, 0, '2022-12-19 09:58:42'),
(23, 'Ksi 7', 6, 16, 0, 8, 0, 0, 0, 0, 0, '2022-12-19 09:58:42'),
(24, 'Ksi 8', 4, 15, 0, 8, 0, 0, 0, 0, 0, '2022-12-19 09:58:42'),
(25, 'Ksi 9', 5, 5, 0, 8, 0, 0, 0, 0, 0, '2022-12-19 09:58:42'),
(26, 'Nu 1', 14, 8, 0, 9, 0, 0, 0, 0, 0, '2022-12-19 10:02:32'),
(27, 'Nu 2', 16, 8, 0, 9, 0, 0, 0, 0, 0, '2022-12-19 10:02:32'),
(28, 'Nu 3', 25, 9, 0, 9, 0, 0, 0, 0, 0, '2022-12-19 10:02:32'),
(29, 'Nu 4', 23, 7, 0, 9, 0, 0, 0, 0, 0, '2022-12-19 10:02:32'),
(30, 'Nu 5', 5, 9, 0, 9, 0, 0, 0, 0, 0, '2022-12-19 10:02:32'),
(31, 'Nu 6', 7, 12, 0, 9, 0, 0, 0, 0, 0, '2022-12-19 10:02:32'),
(32, 'Nu 7', 14, 5, 0, 9, 0, 0, 0, 0, 0, '2022-12-19 10:02:32'),
(33, 'Nu 8', 1, 1, 0, 9, 0, 0, 0, 0, 0, '2022-12-19 10:02:32'),
(34, 'Nu 9', 20, 6, 0, 9, 0, 0, 0, 0, 0, '2022-12-19 10:02:32'),
(35, 'Nu 10', 5, 11, 0, 9, 0, 0, 0, 0, 0, '2022-12-19 10:02:32'),
(36, 'Sigma 1', 6, 8, 0, 10, 0, 0, 0, 0, 0, '2022-12-19 10:21:18'),
(37, 'Sigma 2', 25, 5, 0, 10, 0, 0, 0, 0, 0, '2022-12-19 10:21:18'),
(38, 'Sigma 3', 12, 14, 0, 10, 0, 0, 0, 0, 0, '2022-12-19 10:21:18');

-- --------------------------------------------------------

--
-- Structure de la table `systeme`
--

DROP TABLE IF EXISTS `systeme`;
CREATE TABLE IF NOT EXISTS `systeme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `creato` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `systeme`
--

INSERT INTO `systeme` (`id`, `nom`, `visible`, `creato`) VALUES
(3, 'Lamdba-7058', 0, '2022-12-19 09:46:38'),
(4, 'Epsilon-8594', 0, '2022-12-19 09:49:31'),
(5, 'Epsilon-4556', 0, '2022-12-19 09:50:12'),
(6, 'Nu-1512', 0, '2022-12-19 09:50:51'),
(7, 'Kappa-2984', 0, '2022-12-19 09:58:12'),
(8, 'Ksi-4361', 0, '2022-12-19 09:58:42'),
(9, 'Nu-2343', 0, '2022-12-19 10:02:32'),
(10, 'Sigma-9547', 0, '2022-12-19 10:21:18');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
