<?php

class term_resource extends resource {
	public function __construct($database, $cache = false) {
		parent::__construct('term', $database, $cache);
	}

	public function get_by_list_id($list_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['args'] = [
			'list_term'       => $list_id,
			'master_id'       => 0,
			'term`.`revision' => 0
		];

		return $this->make_query($args)->get_result();
	}
}
