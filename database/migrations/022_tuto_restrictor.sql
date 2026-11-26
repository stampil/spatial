ALTER TABLE `joueur` ADD COLUMN `tuto_aneantie` tinyint(1) NOT NULL DEFAULT '0';

UPDATE `joueur` SET `etape_tuto` = 15 WHERE `etape_tuto` = 14;
