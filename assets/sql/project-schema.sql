CREATE TABLE IF NOT EXISTS `project` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `default_language_id` int(10) unsigned NOT NULL,
    `title` tinytext NOT NULL,
    `descrip` text NULL,
    PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
