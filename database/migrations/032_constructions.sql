CREATE TABLE IF NOT EXISTS `construction` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_joueur` int(11) NOT NULL,
  `id_planete` bigint(20) UNSIGNED NOT NULL,
  `batiment` varchar(32) NOT NULL,
  `fin` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_joueur` (`id_joueur`),
  KEY `id_planete` (`id_planete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
