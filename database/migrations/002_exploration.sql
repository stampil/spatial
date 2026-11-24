CREATE TABLE IF NOT EXISTS `decouverte` (
  `id_joueur` int(11) NOT NULL,
  `id_systeme` int(11) NOT NULL,
  `creato` datetime NOT NULL,
  PRIMARY KEY (`id_joueur`,`id_systeme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `joueur`
  ADD COLUMN `id_systeme_voisin` int(11) DEFAULT NULL AFTER `id_camp`;

INSERT IGNORE INTO `decouverte` (`id_joueur`, `id_systeme`, `creato`)
SELECT `id`, `id_systeme`, now() FROM (
  SELECT j.id AS id, p.id_systeme AS id_systeme FROM `joueur` j JOIN `planete` p ON p.gouverneur = j.id
  UNION
  SELECT pe.id_joueur AS id, pl.id_systeme AS id_systeme FROM `perso` pe JOIN `planete` pl ON pl.id = pe.sur_planete
) AS connus;

UPDATE `joueur` SET `etape_tuto` = 5 WHERE `etape_tuto` = 2;
