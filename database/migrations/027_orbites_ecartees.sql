ALTER TABLE `planete` ADD INDEX `tmp_positions_systeme` (`id_systeme`);
UPDATE `planete` p JOIN (SELECT a.`id`, (SELECT COUNT(*) FROM `planete` b WHERE b.`id_systeme` = a.`id_systeme` AND b.`id` < a.`id`) AS `rang` FROM `planete` a) r ON r.`id` = p.`id` SET p.`x` = ROUND((100 + r.`rang` * 30) * COS(r.`rang` * 2.399963229728653 + p.`id_systeme`)), p.`y` = ROUND((100 + r.`rang` * 30) * SIN(r.`rang` * 2.399963229728653 + p.`id_systeme`));
ALTER TABLE `planete` DROP INDEX `tmp_positions_systeme`;
