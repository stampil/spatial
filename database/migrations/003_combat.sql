ALTER TABLE `planete`
  ADD COLUMN `bouclier` int(11) NOT NULL DEFAULT '0' AFTER `id_camp`,
  ADD COLUMN `canon` int(11) NOT NULL DEFAULT '0' AFTER `bouclier`,
  ADD COLUMN `troupes` int(11) NOT NULL DEFAULT '0' AFTER `canon`;

UPDATE `planete` SET `canon` = `slot`, `bouclier` = `slot` * 2, `troupes` = `slot` DIV 2 + 1 WHERE `id_camp` IS NOT NULL AND `gouverneur` = 0;
