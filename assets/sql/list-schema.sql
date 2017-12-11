CREATE TABLE IF NOT EXISTS `list` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `master_id` int(10) unsigned NOT NULL DEFAULT 0,
    `project_id` int(10) unsigned NOT NULL,
    `user_id` int(10) unsigned NOT NULL,
    `title` tinytext NOT NULL,
    `descrip` text NULL,
    `created` datetime NULL,
    `updated` datetime NULL,
    `revision` tinyint(3) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
