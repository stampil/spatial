ALTER TABLE `planete`
  ADD COLUMN `nb_bouclier_planetaire` int(11) NOT NULL DEFAULT '0',
  ADD COLUMN `nb_canon_orbital` int(11) NOT NULL DEFAULT '0',
  ADD COLUMN `pnj_endurance` tinyint(1) DEFAULT NULL,
  ADD COLUMN `occupee` tinyint(1) NOT NULL DEFAULT '0';

CREATE TABLE IF NOT EXISTS `analyse_planete` (
  `id_joueur` int(11) NOT NULL,
  `id_planete` bigint(20) UNSIGNED NOT NULL,
  `donnees` text NOT NULL,
  `analyse_le` datetime NOT NULL,
  PRIMARY KEY (`id_joueur`,`id_planete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

UPDATE `planete` p JOIN `systeme` s ON s.`id` = p.`id_systeme` SET p.`pnj_endurance` = 1 + (p.`id` MOD 3) WHERE p.`gouverneur` = 0 AND s.`visible` = 1 AND (p.`id` MOD 4) <> 0;

UPDATE `planete` SET `nb_bouclier_planetaire` = 1, `nb_canon_orbital` = 1, `defense_maj` = NOW() WHERE `gouverneur` = 0 AND `id_camp` IS NOT NULL AND `slot` >= 6;

UPDATE `joueur` SET `etape_tuto` = IF(`etape_tuto` = 9, 11, `etape_tuto` + 3) WHERE `etape_tuto` >= 9;
