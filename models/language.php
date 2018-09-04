<?php

class language_model extends model {
	public function __construct($database, $cache = false) {
		parent::__construct('language', $database, $cache);

		$this->register_child_relation('projects', 'project', 'get_by_language_id');
		$this->register_child_relation('users',    'user',    'get_by_language_id');
	}

	public function get_by_project_id($proj_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['bridge'] = 'pl_language';
		$args['args'] = ['pl_project' => $proj_id];

		return $this->make_query($args)->get_result();
	}

	public function get_by_user_id($user_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['bridge'] = 'ul_language';
		$args['args'] = ['ul_user' => $user_id];

		return $this->make_query($args)->get_result();
	}

	protected function get_default_args() {
		$args = parent::get_default_args();
		$args['sort'] = ['code' => 'asc'];

		return $args;
	}
}
