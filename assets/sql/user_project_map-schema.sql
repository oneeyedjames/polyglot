CREATE TABLE IF NOT EXISTS `user_project_map` (
    `user_id` int(10) unsigned NOT NULL,
    `project_id` int(10) unsigned NOT NULL,
    `role_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`user_id`,`project_id`)
) DEFAULT CHARSET=utf8;
