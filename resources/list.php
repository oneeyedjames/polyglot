<?php

class list_resource extends resource {
	public function __construct($database, $cache = false) {
		parent::__construct('list', $database, $cache);
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
