<?php

class project_resource extends resource {
	public function __construct($database, $cache = false) {
		parent::__construct('project', $database, $cache);
	}

	public function get_by_user_id($user_id) {
		return $this->make_query([
			'bridge' => 'up_project',
			'args'   => [
				'up_user' => $user_id
			]
		])->get_result();
	}
}
