CREATE TABLE IF NOT EXISTS `user` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `alias` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `admin` tinyint(3) unsigned NOT NULL DEFAULT 0,
    `password` varchar(128) NOT NULL,
    `reset_token` varchar (255) NULL,
	`reset_expire` datetime NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `alias` (`alias`),
    UNIQUE KEY `email` (`email`)
) DEFAULT CHARSET=utf8;
