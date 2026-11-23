ALTER TABLE `perso`
  ADD COLUMN `action_diplomatie` tinyint(1) DEFAULT NULL AFTER `fin_voyage_planetaire`,
  ADD COLUMN `fin_prison` datetime DEFAULT NULL AFTER `action_diplomatie`,
  ADD COLUMN `fin_mort` datetime DEFAULT NULL AFTER `fin_prison`;

ALTER TABLE `planete`
  ADD COLUMN `revolte_maj` datetime DEFAULT NULL AFTER `nb_academie`;

ALTER TABLE `joueur`
  ADD COLUMN `credits_maj` datetime DEFAULT NULL AFTER `lastco`,
  ADD COLUMN `id_camp` int(11) NOT NULL AFTER `credits_maj`;

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `camp` (`id`, `nom`, `histoire`, `jouable`) VALUES
(1, 'Pirates', 'Anciens équipages de convoyeurs, mercenaires et déserteurs restés sans employeur quand les routes commerciales se sont effondrées. Sans capitale ni loi, ils vivent des cargaisons qu''ils interceptent et détournent les ressources des planètes sans jamais les conquérir. Ils ne se battent entre eux que pour un partage de butin, et personne ne sait où ils se cachent tant qu''on ne les a pas découverts.', 0),
(2, 'Empire', 'Un ordre ancien qui a unifié les systèmes par la force de ses flottes. Son industrie de masse produit unités et ressources à moindre coût, mais ses bannières flottent sur chaque planète conquise : l''Empire est toujours visible sur la carte.', 1),
(3, 'Rébellion', 'Des planètes lassées de l''ordre impérial, des mutins et des ingénieurs libres ont choisi la discrétion. Incapable de rivaliser en nombre, la Rébellion gagne en restant invisible tant qu''on ne l''a pas découverte.', 1);
