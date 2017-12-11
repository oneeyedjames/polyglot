CREATE TABLE IF NOT EXISTS `language` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `code` varchar(10) NOT NULL,
    `name` tinytext NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `code` (`code`)
) DEFAULT CHARSET=utf8;
