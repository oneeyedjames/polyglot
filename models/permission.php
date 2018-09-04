<?php

class permission_model extends model {
	public function __construct($database, $cache) {
		parent::__construct('permission', $database, $cache);
	}

	public function get_by_role_id($role_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
        $args = compact('limit', 'offset');
        $args['bridge'] = 'rp_permission';
        $args['args'] = ['rp_role' => $role_id];
		$args['sort'] = ['resource' => 'asc', 'action' => 'asc'];

        return $this->make_query($args)->get_result();
    }

	public function get_by_resource_action($resource, $action) {
		$args = ['args' => compact('resource', 'action')];

		return $this->make_query($args)->get_result();
	}
}
