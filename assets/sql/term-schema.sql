CREATE TABLE IF NOT EXISTS `term` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `master_id` int(10) unsigned NOT NULL DEFAULT 0,
    `language_id` int(10) unsigned NOT NULL,
    `list_id` int(10) unsigned NOT NULL,
    `user_id` int(10) unsigned NOT NULL,
    `content` text NULL,
    `descrip` text NULL,
    `created` datetime NULL,
    `updated` datetime NULL,
    `revision` tinyint(3) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
