ALTER TABLE `joueur`
  ADD COLUMN `fin_tuto` datetime DEFAULT NULL AFTER `id_systeme_voisin`;

UPDATE `joueur` SET `fin_tuto` = now() WHERE `etape_tuto` >= 8;
