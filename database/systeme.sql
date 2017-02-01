-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mer 01 Février 2017 à 16:49
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `systeme`
--

-- --------------------------------------------------------

--
-- Structure de la table `joueur`
--

CREATE TABLE `joueur` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sur_planete` bigint(20) UNSIGNED DEFAULT NULL,
  `mdp` varchar(255) NOT NULL,
  `creato` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `joueur`
--

INSERT INTO `joueur` (`id`, `nom`, `email`, `sur_planete`, `mdp`, `creato`) VALUES
(1, 'stampil', 'toto@gmail.com', 1, 'eHXnFV9Qwdj5Y8EntbFQ/GHcpjEPTz4fBde2ygK2V2P5XrtnxmOSR1uMX2g45rptzDpV4ErvAMDJ.a0xXbxGr0', '2017-02-01 17:09:14'),
(2, 'detrios', 'titi@gmail.com', 1, 'eHXnFV9Qwdj5Y8EntbFQ/GHcpjEPTz4fBde2ygK2V2P5XrtnxmOSR1uMX2g45rptzDpV4ErvAMDJ.a0xXbxGr0', '2017-02-01 17:29:19');

-- --------------------------------------------------------

--
-- Structure de la table `planete`
--

CREATE TABLE `planete` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `perc_revolte` int(2) NOT NULL,
  `slot` int(2) NOT NULL,
  `id_systeme` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `planete`
--

INSERT INTO `planete` (`id`, `nom`, `perc_revolte`, `slot`, `id_systeme`) VALUES
(1, 'topi', 5, 8, 1),
(2, 'tto', 35, 12, 1);

-- --------------------------------------------------------

--
-- Structure de la table `systeme`
--

CREATE TABLE `systeme` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `systeme`
--

INSERT INTO `systeme` (`id`, `nom`) VALUES
(1, 'demo');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `joueur`
--
ALTER TABLE `joueur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Index pour la table `planete`
--
ALTER TABLE `planete`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `systeme`
--
ALTER TABLE `systeme`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `joueur`
--
ALTER TABLE `joueur`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `planete`
--
ALTER TABLE `planete`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `systeme`
--
ALTER TABLE `systeme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
