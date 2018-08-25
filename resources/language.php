<?php

class language_resource extends resource {
	public function __construct($database, $cache = false) {
		parent::__construct('language', $database, $cache);
	}

	public function get_by_id($lang_id) {
		return $this->make_query([
			'args' => ['id' => $lang_id]
		])->get_result();
	}

	public function get_by_user_id($user_id) {
		return $this->make_query([
			'bridge' => 'ul_language',
			'args'   => [
				'ul_user' => $user_id
			]
		])->get_result();
	}

	public function get_by_project_id($proj_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['bridge'] = 'pl_language';
		$args['args'] = ['pl_project' => $proj_id];

		return $this->make_query($args)->get_result();
	}
}
