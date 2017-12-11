CREATE TABLE IF NOT EXISTS `user_language_map` (
    `user_id` int(10) unsigned NOT NULL,
    `language_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`user_id`,`language_id`)
) DEFAULT CHARSET=utf8;
