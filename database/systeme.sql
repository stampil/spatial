-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 10 Septembre 2019 à 17:49
-- Version du serveur :  5.5.39
-- Version de PHP :  5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `systeme`
--
CREATE DATABASE IF NOT EXISTS `systeme` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `systeme`;

-- --------------------------------------------------------

--
-- Structure de la table `joueur`
--

DROP TABLE IF EXISTS `joueur`;
CREATE TABLE IF NOT EXISTS `joueur` (
`id` bigint(20) unsigned NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `IPs` text NOT NULL,
  `credits` int(11) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `etape_tuto` tinyint(1) NOT NULL DEFAULT '0',
  `creato` datetime NOT NULL,
  `lastco` datetime NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `perso`
--

DROP TABLE IF EXISTS `perso`;
CREATE TABLE IF NOT EXISTS `perso` (
`id` int(11) NOT NULL,
  `id_joueur` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `PV` int(11) NOT NULL COMMENT 'Vie',
  `FO` int(11) NOT NULL COMMENT 'FORCE',
  `diplomatie` int(11) NOT NULL,
  `sur_planete` int(11) NOT NULL,
  `fin_diplomatie` datetime NOT NULL,
  `fin_voyage_planetaire` datetime NOT NULL,
  `creato` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `planete`
--

DROP TABLE IF EXISTS `planete`;
CREATE TABLE IF NOT EXISTS `planete` (
`id` bigint(20) unsigned NOT NULL,
  `nom` varchar(255) NOT NULL,
  `perc_revolte` int(2) NOT NULL,
  `slot` int(2) NOT NULL,
  `gouverneur` int(11) NOT NULL,
  `id_systeme` bigint(20) unsigned DEFAULT NULL,
  `nb_usine_vaiss_leger` int(11) NOT NULL,
  `nb_usine_vaiss_moyen` int(11) NOT NULL,
  `nb_usine_vaiss_lourd` int(11) NOT NULL,
  `nb_usine_ressource` int(11) NOT NULL,
  `nb_academie` int(11) NOT NULL,
  `creato` datetime NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Structure de la table `systeme`
--

DROP TABLE IF EXISTS `systeme`;
CREATE TABLE IF NOT EXISTS `systeme` (
`id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `creato` datetime NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `joueur`
--
ALTER TABLE `joueur`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`), ADD UNIQUE KEY `nom` (`nom`);

--
-- Index pour la table `perso`
--
ALTER TABLE `perso`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `planete`
--
ALTER TABLE `planete`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `systeme`
--
ALTER TABLE `systeme`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `nom` (`nom`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `joueur`
--
ALTER TABLE `joueur`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `perso`
--
ALTER TABLE `perso`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `planete`
--
ALTER TABLE `planete`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `systeme`
--
ALTER TABLE `systeme`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
