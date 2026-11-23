ALTER TABLE `perso`
  ADD COLUMN `action_diplomatie` tinyint(1) DEFAULT NULL AFTER `fin_voyage_planetaire`,
  ADD COLUMN `fin_prison` datetime DEFAULT NULL AFTER `action_diplomatie`,
  ADD COLUMN `fin_mort` datetime DEFAULT NULL AFTER `fin_prison`;

ALTER TABLE `planete`
  ADD COLUMN `revolte_maj` datetime DEFAULT NULL AFTER `nb_academie`;

ALTER TABLE `joueur`
  ADD COLUMN `credits_maj` datetime DEFAULT NULL AFTER `lastco`;

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
