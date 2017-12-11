CREATE TABLE IF NOT EXISTS `document` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `master_id` int(10) unsigned NOT NULL DEFAULT 0,
    `language_id` int(10) unsigned NOT NULL,
    `project_id` int(10) unsigned NOT NULL,
    `user_id` int(10) unsigned NOT NULL,
    `title` tinytext NOT NULL,
    `content` text NULL,
    `descrip` text NULL,
    `created` datetime NULL,
    `updated` datetime NULL,
    `revision` tinyint(3) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `project_id` (`project_id`)
) DEFAULT CHARSET=utf8;
