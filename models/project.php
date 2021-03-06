<?php

class project_model extends model {
	public function __construct($database, $cache = false) {
		parent::__construct('project', $database, $cache);

		$this->register_parent_relation('default_language', 'language', 'default_language_id');

		$this->register_child_relation('languages', 'language', 'get_by_project_id');
		$this->register_child_relation('users',     'user',     'get_by_project_id');
		$this->register_child_relation('documents', 'document', 'get_by_project_id');
		$this->register_child_relation('lists',     'list',     'get_by_project_id');
	}

	public function get_by_language_id($lang_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['bridge'] = 'pl_project';
		$args['args']   = ['pl_language' => $lang_id];

		return $this->make_query($args)->get_result();
	}

	public function get_by_user_id($user_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['bridge'] = 'up_project';
		$args['args']   = ['up_user' => $user_id];

		return $this->make_query($args)->get_result();
	}

	public function add_language($proj_id, $lang_id) {
		$sql = 'INSERT INTO `project_language_map` (`project_id`, `language_id`) VALUES (?, ?)';
		return $this->execute($sql, intval($proj_id), intval($lang_id));
	}

	public function remove_language($proj_id, $lang_id) {
		$sql = 'DELETE FROM `project_language_map` WHERE `project_id` = ? AND `language_id` = ?';
		return $this->execute($sql, intval($proj_id), intval($lang_id));
	}

	public function add_user($proj_id, $user_id, $role_id) {
		$sql = 'INSERT INTO `user_project_map` (`project_id`, `user_id`, `role_id`) VALUES (?, ?, ?)';
		return $this->execute($sql, intval($proj_id), intval($user_id), intval($role_id));
	}

	public function remove_user($proj_id, $user_id) {
		$sql = 'DELETE FROM `user_project_map` WHERE `project_id` = ? AND `user_id` = ?';
		return $this->execute($sql, intval($proj_id), intval($user_id));
	}
}
