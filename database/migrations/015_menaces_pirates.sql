DROP TABLE IF EXISTS `defi`;

CREATE TABLE IF NOT EXISTS `menace` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_joueur` bigint(20) UNSIGNED NOT NULL,
  `id_planete` bigint(20) UNSIGNED NOT NULL,
  `id_source` bigint(20) UNSIGNED NOT NULL,
  `creato` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `joueur` (`id_joueur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
