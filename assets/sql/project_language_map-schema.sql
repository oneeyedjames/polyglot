CREATE TABLE IF NOT EXISTS `project_language_map` (
    `project_id` int(10) unsigned NOT NULL,
    `language_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`project_id`,`language_id`)
) DEFAULT CHARSET=utf8;
