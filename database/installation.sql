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
  `credits_maj` datetime DEFAULT NULL,
  `id_camp` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `action_diplomatie` tinyint(1) DEFAULT NULL,
  `fin_prison` datetime DEFAULT NULL,
  `fin_mort` datetime DEFAULT NULL,
  `creato` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `revolte_maj` datetime DEFAULT NULL,
  `id_camp` int(11) DEFAULT NULL,
  `creato` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `systeme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `creato` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `production` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_joueur` int(11) NOT NULL,
  `id_planete` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(32) NOT NULL,
  `fin` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_joueur` (`id_joueur`),
  KEY `id_planete` (`id_planete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `vaisseau` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_joueur` int(11) NOT NULL,
  `id_planete` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(32) NOT NULL,
  `creato` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_joueur` (`id_joueur`),
  KEY `id_planete` (`id_planete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `camp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `histoire` text NOT NULL,
  `jouable` tinyint(1) NOT NULL,
  `couleur` varchar(7) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `camp` (`id`, `nom`, `histoire`, `jouable`, `couleur`) VALUES
(1, 'Pirates', 'Anciens équipages de convoyeurs, mercenaires et déserteurs restés sans employeur quand les routes commerciales se sont effondrées. Sans capitale ni loi, ils vivent des cargaisons qu''ils interceptent et détournent les ressources des planètes sans jamais les conquérir. Ils ne se battent entre eux que pour un partage de butin, et personne ne sait où ils se cachent tant qu''on ne les a pas découverts.', 1, '#B3261C'),
(2, 'Empire', 'Un ordre ancien qui a unifié les systèmes par la force de ses flottes. Son industrie de masse produit unités et ressources à moindre coût, mais ses bannières flottent sur chaque planète conquise : l''Empire est toujours visible sur la carte.', 1, '#1565C0'),
(3, 'Rébellion', 'Des planètes lassées de l''ordre impérial, des mutins et des ingénieurs libres ont choisi la discrétion. Incapable de rivaliser en nombre, la Rébellion gagne en restant invisible tant qu''on ne l''a pas découverte.', 1, '#2E7D32');
