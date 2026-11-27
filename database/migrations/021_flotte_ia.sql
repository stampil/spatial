ALTER TABLE `planete` ADD COLUMN `flotte_ia` int(11) NOT NULL DEFAULT '0';

UPDATE `planete` SET `flotte_ia` = IF(`id_camp` = 2, `slot`, GREATEST(1, `slot` DIV 2)) WHERE `gouverneur` = 0 AND `id_camp` IN (1, 2);
