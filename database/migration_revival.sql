ALTER TABLE `perso`
  ADD COLUMN `action_diplomatie` tinyint(1) DEFAULT NULL AFTER `fin_voyage_planetaire`,
  ADD COLUMN `fin_prison` datetime DEFAULT NULL AFTER `action_diplomatie`,
  ADD COLUMN `fin_mort` datetime DEFAULT NULL AFTER `fin_prison`,
  ADD COLUMN `embarque` tinyint(1) NOT NULL DEFAULT '0' AFTER `fin_mort`,
  ADD COLUMN `id_destination` bigint(20) UNSIGNED DEFAULT NULL AFTER `embarque`,
  ADD COLUMN `arrivee` datetime DEFAULT NULL AFTER `id_destination`,
  ADD COLUMN `role` varchar(16) NOT NULL DEFAULT 'diplomate' AFTER `arrivee`;

ALTER TABLE `planete`
  ADD COLUMN `revolte_maj` datetime DEFAULT NULL AFTER `nb_academie`,
  ADD COLUMN `id_camp` int(11) DEFAULT NULL AFTER `revolte_maj`,
  ADD COLUMN `bouclier` int(11) NOT NULL DEFAULT '0' AFTER `id_camp`,
  ADD COLUMN `canon` int(11) NOT NULL DEFAULT '0' AFTER `bouclier`,
  ADD COLUMN `troupes` int(11) NOT NULL DEFAULT '0' AFTER `canon`,
  ADD COLUMN `bonus` varchar(16) DEFAULT NULL AFTER `troupes`,
  ADD COLUMN `defense_maj` datetime DEFAULT NULL AFTER `bonus`,
  ADD COLUMN `detournement_fin` datetime DEFAULT NULL AFTER `defense_maj`,
  ADD COLUMN `flotte_ia` int(11) NOT NULL DEFAULT '0' AFTER `creato`,
  ADD COLUMN `nb_bouclier_planetaire` int(11) NOT NULL DEFAULT '0' AFTER `flotte_ia`,
  ADD COLUMN `nb_canon_orbital` int(11) NOT NULL DEFAULT '0' AFTER `nb_bouclier_planetaire`,
  ADD COLUMN `pnj_endurance` tinyint(1) DEFAULT NULL AFTER `nb_canon_orbital`,
  ADD COLUMN `occupee` tinyint(1) NOT NULL DEFAULT '0' AFTER `pnj_endurance`,
  ADD COLUMN `x` int(11) NOT NULL DEFAULT '0' AFTER `occupee`,
  ADD COLUMN `y` int(11) NOT NULL DEFAULT '0' AFTER `x`,
  ADD COLUMN `nom_origine` varchar(255) DEFAULT NULL AFTER `y`;

ALTER TABLE `systeme`
  ADD COLUMN `x` int(11) NOT NULL DEFAULT '0' AFTER `visible`,
  ADD COLUMN `y` int(11) NOT NULL DEFAULT '0' AFTER `x`;

ALTER TABLE `joueur`
  ADD COLUMN `credits_maj` datetime DEFAULT NULL AFTER `lastco`,
  ADD COLUMN `id_camp` int(11) NOT NULL AFTER `credits_maj`,
  ADD COLUMN `id_systeme_voisin` int(11) DEFAULT NULL AFTER `id_camp`,
  ADD COLUMN `fin_tuto` datetime DEFAULT NULL AFTER `id_systeme_voisin`,
  ADD COLUMN `raid_maj` datetime DEFAULT NULL AFTER `fin_tuto`,
  ADD COLUMN `aide_icones_masquee` tinyint(1) NOT NULL DEFAULT '0' AFTER `raid_maj`,
  ADD COLUMN `tuto_aneantie` tinyint(1) NOT NULL DEFAULT '0' AFTER `aide_icones_masquee`,
  ADD COLUMN `credits_reste` int(11) NOT NULL DEFAULT '0' AFTER `tuto_aneantie`;

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
  `id_destination` bigint(20) UNSIGNED DEFAULT NULL,
  `arrivee` datetime DEFAULT NULL,
  `depart` datetime DEFAULT NULL,
  `exploration` tinyint(1) NOT NULL DEFAULT '0',
  `id_flotte` bigint(20) UNSIGNED DEFAULT NULL,
  `numero` int(11) UNSIGNED DEFAULT NULL,
  `nom` varchar(32) DEFAULT NULL,
  `id_hote` bigint(20) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_joueur` (`id_joueur`),
  KEY `id_planete` (`id_planete`),
  KEY `id_flotte` (`id_flotte`),
  KEY `id_hote` (`id_hote`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flotte` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_joueur` int(11) NOT NULL,
  `nom` varchar(64) NOT NULL,
  `creato` datetime NOT NULL,
  `numero` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_joueur` (`id_joueur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `analyse_planete` (
  `id_joueur` int(11) NOT NULL,
  `id_planete` bigint(20) UNSIGNED NOT NULL,
  `donnees` text NOT NULL,
  `analyse_le` datetime NOT NULL,
  PRIMARY KEY (`id_joueur`,`id_planete`)
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
(1, 'Pirates', 'Anciens équipages de convoyeurs, mercenaires et déserteurs restés sans employeur quand les routes commerciales se sont effondrées. Sans capitale ni loi, ils vivent des cargaisons qu''ils interceptent et détournent les ressources des planètes sans jamais les conquérir. Ils ne se battent entre eux que pour un partage de butin, et personne ne sait où ils se cachent tant qu''on ne les a pas découverts.', 0, '#B3261C'),
(2, 'Empire', 'Un ordre ancien qui a unifié les systèmes par la force de ses flottes. Son industrie de masse produit unités et ressources à moindre coût, mais ses bannières flottent sur chaque planète conquise : l''Empire est toujours visible sur la carte.', 1, '#1565C0'),
(3, 'Rébellion', 'Des planètes lassées de l''ordre impérial, des mutins et des ingénieurs libres ont choisi la discrétion. Incapable de rivaliser en nombre, la Rébellion gagne en restant invisible tant qu''on ne l''a pas découverte.', 1, '#2E7D32');

CREATE TABLE IF NOT EXISTS `decouverte` (
  `id_joueur` int(11) NOT NULL,
  `id_systeme` int(11) NOT NULL,
  `creato` datetime NOT NULL,
  PRIMARY KEY (`id_joueur`,`id_systeme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `journal` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_joueur` int(11) NOT NULL,
  `message` varchar(500) NOT NULL,
  `creato` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_joueur` (`id_joueur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `menace` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_joueur` bigint(20) UNSIGNED NOT NULL,
  `id_planete` bigint(20) UNSIGNED NOT NULL,
  `id_source` bigint(20) UNSIGNED NOT NULL,
  `creato` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `joueur` (`id_joueur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jeton_connexion` (
  `empreinte` char(64) NOT NULL,
  `id_joueur` int(11) NOT NULL,
  `creato` datetime NOT NULL,
  PRIMARY KEY (`empreinte`),
  KEY `id_joueur` (`id_joueur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `schema_migrations` (
  `version` varchar(64) NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `schema_migrations` (`version`) VALUES
('001_vaisseau_deplacement'),
('002_exploration'),
('003_combat'),
('004_fin_du_tutoriel'),
('005_visibilite'),
('006_flottes'),
('007_persos_transport'),
('008_roles'),
('009_carte'),
('010_journal'),
('011_defense_pirates_ia'),
('012_pirates_jouables'),
('013_sans_email'),
('014_pirates_ia'),
('015_menaces_pirates'),
('016_exploration_risquee'),
('017_connexion_automatique'),
('018_vaisseaux_nommes_embarques'),
('019_aide_icones_masquee'),
('020_flotte_numero'),
('021_flotte_ia'),
('022_tuto_restrictor'),
('023_gouverneurs_structures_analyse'),
('024_perso_demi_etoiles'),
('025_positions_planetes'),
('026_planete_nom_origine'),
('027_orbites_ecartees'),
('028_credits_reste');

UPDATE `perso` SET `PV` = `PV` * 2, `FO` = `FO` * 2, `diplomatie` = `diplomatie` * 2;

ALTER TABLE `planete` ADD INDEX `tmp_positions_systeme` (`id_systeme`);
UPDATE `planete` p JOIN (SELECT a.`id`, (SELECT COUNT(*) FROM `planete` b WHERE b.`id_systeme` = a.`id_systeme` AND b.`id` < a.`id`) AS `rang` FROM `planete` a) r ON r.`id` = p.`id` SET p.`x` = ROUND((100 + r.`rang` * 30) * COS(r.`rang` * 2.399963229728653 + p.`id_systeme`)), p.`y` = ROUND((100 + r.`rang` * 30) * SIN(r.`rang` * 2.399963229728653 + p.`id_systeme`));
ALTER TABLE `planete` DROP INDEX `tmp_positions_systeme`;

UPDATE `systeme` SET `x` = 500 + ROUND(60 * SQRT(`id`) * COS(`id` * 2.39996323)), `y` = 500 + ROUND(60 * SQRT(`id`) * SIN(`id` * 2.39996323));

UPDATE `planete` SET `bonus` = ELT(1 + `id` % 5, 'chantier', 'forge', 'marche', 'citadelle', 'relais') WHERE `id_systeme` IN (SELECT `id` FROM `systeme` WHERE `visible` = 1);

UPDATE `planete` SET `defense_maj` = NOW() WHERE `id_camp` IS NOT NULL AND (`bouclier` < `slot` * 2 OR `canon` < `slot`);

ALTER TABLE `joueur` DROP INDEX `email`, DROP COLUMN `email`;
