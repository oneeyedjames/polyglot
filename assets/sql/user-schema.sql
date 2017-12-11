CREATE TABLE IF NOT EXISTS `user` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `password` varchar(128) NOT NULL,
    `email` varchar(255) NOT NULL,
    `admin` tinyint(3) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) DEFAULT CHARSET=utf8;
