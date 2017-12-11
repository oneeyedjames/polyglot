CREATE TABLE IF NOT EXISTS `role` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `title` tinytext NOT NULL,
    `descrip` text NULL,
    PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
