ALTER TABLE `joueur`
  CHANGE COLUMN `tuto_transit_lu` `tuto_explication_lue` int(11) NOT NULL DEFAULT '0';
UPDATE `joueur` SET `tuto_explication_lue` = 4 WHERE `tuto_explication_lue` = 1;
