CREATE TABLE IF NOT EXISTS `flotte` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_joueur` int(11) NOT NULL,
  `nom` varchar(64) NOT NULL,
  `creato` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_joueur` (`id_joueur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `vaisseau`
  ADD COLUMN `id_flotte` bigint(20) UNSIGNED DEFAULT NULL AFTER `arrivee`,
  ADD KEY `id_flotte` (`id_flotte`);
