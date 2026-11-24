ALTER TABLE `perso`
  ADD COLUMN `role` varchar(16) NOT NULL DEFAULT 'diplomate' AFTER `arrivee`;
