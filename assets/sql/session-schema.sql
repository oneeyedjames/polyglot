CREATE TABLE IF NOT EXISTS `session` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(10) unsigned NOT NULL,
    `token` varchar(100) NOT NULL,
    `expire` datetime NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `token` (`token`),
    KEY `user_id` (`user_id`)
) DEFAULT CHARSET=utf8;
