ALTER TABLE `systeme`
  ADD COLUMN `x` int(11) NOT NULL DEFAULT '0' AFTER `visible`,
  ADD COLUMN `y` int(11) NOT NULL DEFAULT '0' AFTER `x`;

ALTER TABLE `planete`
  ADD COLUMN `bonus` varchar(16) DEFAULT NULL AFTER `troupes`;

ALTER TABLE `vaisseau`
  ADD COLUMN `depart` datetime DEFAULT NULL AFTER `arrivee`;

UPDATE `systeme` SET `x` = 500 + ROUND(60 * SQRT(`id`) * COS(`id` * 2.39996323)), `y` = 500 + ROUND(60 * SQRT(`id`) * SIN(`id` * 2.39996323));

UPDATE `planete` SET `bonus` = ELT(1 + `id` % 5, 'chantier', 'forge', 'marche', 'citadelle', 'relais') WHERE `id_systeme` IN (SELECT `id` FROM `systeme` WHERE `visible` = 1);
