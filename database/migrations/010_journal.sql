CREATE TABLE IF NOT EXISTS `journal` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_joueur` int(11) NOT NULL,
  `message` varchar(500) NOT NULL,
  `creato` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_joueur` (`id_joueur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
