ALTER TABLE `flotte` ADD COLUMN `numero` int(11) UNSIGNED DEFAULT NULL;

UPDATE `flotte` f JOIN (SELECT a.`id`, COUNT(*) AS `rang` FROM `flotte` a JOIN `flotte` b ON b.`id_joueur` = a.`id_joueur` AND b.`id` <= a.`id` GROUP BY a.`id`) r ON r.`id` = f.`id` SET f.`numero` = r.`rang`;
