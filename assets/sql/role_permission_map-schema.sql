CREATE TABLE IF NOT EXISTS `role_permission_map` (
    `role_id` int(10) unsigned NOT NULL,
    `permission_id` int(10) unsigned NOT NULL,
    `override` tinyint(3) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY (`role_id`,`permission_id`)
) DEFAULT CHARSET=utf8;
