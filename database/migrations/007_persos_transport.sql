ALTER TABLE `perso`
  ADD COLUMN `embarque` tinyint(1) NOT NULL DEFAULT '0' AFTER `fin_mort`,
  ADD COLUMN `id_destination` bigint(20) UNSIGNED DEFAULT NULL AFTER `embarque`,
  ADD COLUMN `arrivee` datetime DEFAULT NULL AFTER `id_destination`;
