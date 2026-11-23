ALTER TABLE `perso`
  ADD COLUMN `action_diplomatie` tinyint(1) DEFAULT NULL AFTER `fin_voyage_planetaire`,
  ADD COLUMN `fin_prison` datetime DEFAULT NULL AFTER `action_diplomatie`,
  ADD COLUMN `fin_mort` datetime DEFAULT NULL AFTER `fin_prison`;

ALTER TABLE `planete`
  ADD COLUMN `revolte_maj` datetime DEFAULT NULL AFTER `nb_academie`;
