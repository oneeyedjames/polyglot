CREATE TABLE IF NOT EXISTS `permission` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `resource` varchar(128) NOT NULL,
    `action` varchar(128) NOT NULL,
    PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
