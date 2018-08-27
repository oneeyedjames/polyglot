<?php

class project_resource extends resource {
	public function __construct($database, $cache = false) {
		parent::__construct('project', $database, $cache);
	}

	public function get_record($proj_id, $rels = []) {
		if ($project = parent::get_record($proj_id)) {
			if (in_array('language', $rels))
				$project->languages = resource::load('language')->get_by_project_id($proj_id);

			if (in_array('user', $rels))
				$project->users = resource::load('user')->get_by_project_id($proj_id);
		}

		return $project;
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
