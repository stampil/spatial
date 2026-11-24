ALTER TABLE `vaisseau`
  ADD COLUMN `id_destination` bigint(20) UNSIGNED DEFAULT NULL AFTER `creato`,
  ADD COLUMN `arrivee` datetime DEFAULT NULL AFTER `id_destination`;
