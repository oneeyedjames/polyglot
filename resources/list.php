<?php

class list_resource extends resource {
	public function __construct($database, $cache = false) {
		parent::__construct('list', $database, $cache);
	}

	public function get_by_project_id($proj_id) {
		return $this->make_query([
			'args' => [
				'project_list' => $proj_id,
				'master_id'    => 0,
				'revision'     => 0
			]
		], 'list')->get_result();
	}
}
