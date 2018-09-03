<?php

class list_resource extends resource {
	public function __construct($database, $cache = false) {
		parent::__construct('list', $database, $cache);

		$this->register_parent_relation('project', 'project', 'project_id');
		$this->register_parent_relation('user',    'user',    'user_id');

		$this->register_child_relation('terms', 'term', 'get_by_list_id');
	}

	public function get_by_project_id($proj_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['args'] = [
			'project_list' => $proj_id,
			'master_id'    => 0,
			'revision'     => 0
		];

		return $this->make_query($args)->get_result();
	}
}
