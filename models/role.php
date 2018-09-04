<?php

class role_model extends model {
	public function __construct($database, $cache) {
		parent::__construct('role', $database, $cache);

		$this->register_child_relation('permissions', 'permission', 'get_by_role_id');
	}

	public function add_permission($role_id, $perm_id) {
		$sql = 'INSERT INTO `role_permission_map` (`role_id`, `permission_id`) VALUES (?, ?)';
		return $this->execute($sql, intval($role_id), intval($perm_id));
	}

	public function remove_permission($role_id, $perm_id) {
		$sql = 'DELETE FROM `role_permission_map` WHERE `role_id` = ? AND `permission_id` = ?';
		return $this->execute($sql, intval($role_id), intval($perm_id));
	}
}
