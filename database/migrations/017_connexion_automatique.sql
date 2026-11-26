CREATE TABLE IF NOT EXISTS `jeton_connexion` (
  `empreinte` char(64) NOT NULL,
  `id_joueur` int(11) NOT NULL,
  `creato` datetime NOT NULL,
  PRIMARY KEY (`empreinte`),
  KEY `id_joueur` (`id_joueur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
