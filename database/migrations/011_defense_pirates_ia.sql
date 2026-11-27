ALTER TABLE `planete`
  ADD COLUMN `defense_maj` datetime DEFAULT NULL AFTER `bonus`,
  ADD COLUMN `detournement_fin` datetime DEFAULT NULL AFTER `defense_maj`;

ALTER TABLE `joueur`
  ADD COLUMN `raid_maj` datetime DEFAULT NULL;

CREATE TABLE IF NOT EXISTS `defi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_attaquant` bigint(20) UNSIGNED NOT NULL,
  `id_defenseur` bigint(20) UNSIGNED NOT NULL,
  `id_planete` bigint(20) UNSIGNED NOT NULL,
  `creato` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `duel` (`id_attaquant`,`id_defenseur`,`id_planete`),
  KEY `id_defenseur` (`id_defenseur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

UPDATE `planete` SET `defense_maj` = NOW() WHERE `id_camp` IS NOT NULL AND (`bouclier` < `slot` * 2 OR `canon` < `slot`);
