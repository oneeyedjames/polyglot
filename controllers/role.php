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

    public function add_permission_action($get, $post) {
        $role_id = get_resource_id();

        if (isset($post['permission'])) {
            $permission_id = intval($post['permission']);

            $sql = 'SELECT * FROM role_permission_map WHERE role_id = ? AND permission_id = ?';

            $result = $this->query($sql, $role_id, $permission_id);

            if (!$result->found) {
                $record = new object(array(
    				'role_id'       => $role_id,
                    'permission_id' => $permission_id,
                    'granted'       => 1
    			));

    			$this->put_record($record, 'role_permission_map');
            }
        }

        return array('resource' => 'role');
    }

    public function remove_permission_action($get, $post) {
        $role_id = get_resource_id();

        if (isset($post['permission'])) {
            $permission_id = intval($post['permission']);

            $sql = 'DELETE FROM role_permission_map WHERE role_id = ? AND permission_id = ?';

            $this->execute($sql, $role_id, $permission_id);
        }

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

    public function form_permission_view($vars) {
        $permissions = $this->query('SELECT * FROM permission ORDER BY `resource`, `action`');

        $resources = array();
        foreach ($permissions as $permission)
            $resources[] = $permission->resource;

        $vars['role'] = $this->get_record(get_resource_id());
        $vars['permissions'] = $permissions;
        $vars['resources'] = array_unique($resources);

        return $vars;
    }
}
