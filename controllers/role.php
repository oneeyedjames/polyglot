<?php

class role_controller extends controller {
    public function __construct($database, $cache = null) {
		parent::__construct('role', $database, $cache);
	}

    public function save_action($get, $post) {
        $role = new object();
        $role->id = get_resource_id();

        if (isset($post['role']['title']))
            $role->title = $post['role']['title'];

        if (isset($post['role']['description']))
            $role->descrip = $post['role']['description'];

        $this->put_record($role);

        return array('resource' => 'role');
    }

    public function delete_action($get, $post) {
        if ($role_id = get_resource_id())
            $this->execute('DELETE FROM `role` WHERE `id` = ?', $role_id);

        return array('resource' => 'role');
    }

    public function index_view($vars) {
        $limit  = get_per_page();
		$offset = get_offset(get_page(), $limit);

		$args = compact('limit', 'offset');

        $roles = $this->make_query($args)->get_result();
		$roles->walk(function(&$role) {
            $role->permissions = $this->make_query(array(
				'bridge' => 'rp_permission',
				'args'   => array(
					'rp_role' => $role->id
				)
			), 'permission')->get_result();
		});

		$vars['roles'] = $roles;

        return $vars;
    }

    public function form_meta_view($vars) {
        if ($role_id = get_resource_id())
			$vars['role'] = $this->get_record($role_id);
		else
			$vars['role'] = new object();

		return $vars;
    }
}
