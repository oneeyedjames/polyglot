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

        return ['resource' => 'role'];
    }

    public function add_permission_action($get, $post) {
        $role_id = get_resource_id();

        if (isset($post['permission']['resource'], $post['permission']['action'])) {
            $permissions = $this->make_query([
                'args' => [
                    'resource' => $post['permission']['resource'],
                    'action'   => $post['permission']['action']
                ]
            ], 'permission')->get_result();

            if ($permissions->found) {
                $permission = $permissions->first;
            } else {
                $permission = new object();
                $permission->resource = $post['permission']['resource'];
                $permission->action   = $post['permission']['action'];
                $permission->id       = $this->put_record($permission, 'permission');
            }

			$this->put_record(new object([
				'role_id'       => $role_id,
                'permission_id' => $permission->id
			]), 'role_permission_map');
        }

        return ['resource' => 'role', 'id' => $role_id];
    }

    public function remove_permission_action($get, $post) {
        $role_id = get_resource_id();

        if (isset($post['permission'])) {
            $sql = 'DELETE FROM role_permission_map WHERE role_id = ? AND permission_id = ?';
            $this->execute($sql, $role_id, intval($post['permission']));
        }

        return ['resource' => 'role', 'id' => $role_id];
    }

    public function index_view($vars) {
        $limit  = get_per_page();
		$offset = get_offset(get_page(), $limit);

		$vars['roles'] = $this->get_roles($limit, $offset);

        return $vars;
    }

    public function item_view($vars) {
        if ($role_id = get_resource_id()) {
            $role = $this->get_record($role_id);
            $role->permissions = $this->get_permissions($role_id);

            $vars['role'] = $role;
        }

        return $vars;
    }

    public function form_meta_view($vars) {
        if ($role_id = get_resource_id())
			$vars['role'] = $this->get_record($role_id);
		else
			$vars['role'] = new object();

		return $vars;
    }

    public function form_permission_view($vars) {
        $url_schema = init_url();

        $vars['role'] = $this->get_record(get_resource_id());
        $vars['resources'] = $url_schema->resources;

        return $vars;
    }

    public function card_permissions_view($vars) {
        if ($role_id = get_resource_id()) {
            $role = $this->get_record(get_resource_id());
            $role->permissions = $this->get_permissions($role_id);

            $vars['role'] = $role;
        }

        return $vars;
    }

    protected function get_roles($limit = DEFAULT_PER_PAGE, $offset = 0) {
        $args = compact('limit', 'offset');

        $roles = $this->make_query($args)->get_result();
		$roles->walk(function(&$role) {
            $role->permissions = $this->get_permissions($role->id);
		});

        return $roles;
    }

    protected function get_permissions($role_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
        $args = compact('limit', 'offset');
        $args['bridge'] = 'rp_permission';
        $args['args'] = ['rp_role' => $role_id];

        return $this->make_query($args, 'permission')->get_result();
    }
}
