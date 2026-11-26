ALTER TABLE `vaisseau`
  ADD COLUMN `numero` int(11) UNSIGNED DEFAULT NULL,
  ADD COLUMN `nom` varchar(32) DEFAULT NULL,
  ADD COLUMN `id_hote` bigint(20) UNSIGNED DEFAULT NULL,
  ADD KEY `id_hote` (`id_hote`);

UPDATE `vaisseau` v JOIN (SELECT a.`id`, COUNT(*) AS `rang` FROM `vaisseau` a JOIN `vaisseau` b ON b.`id_joueur` = a.`id_joueur` AND b.`type` = a.`type` AND b.`id` <= a.`id` WHERE a.`type` IN ('fregate', 'barge', 'capital') GROUP BY a.`id`) r ON r.`id` = v.`id` SET v.`numero` = r.`rang`, v.`nom` = CONCAT(ELT(FIELD(v.`type`, 'fregate', 'barge', 'capital'), 'Frégate', 'Barge', 'Capital'), ' ', r.`rang`);
