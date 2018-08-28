<?php

class role_controller extends controller {
    public function save_action($get, $post) {
        $role = new object();
        $role->id = get_resource_id();

		if (isset($post['role'])) {
			if (isset($post['role']['title']))
	            $role->title = $post['role']['title'];

	        if (isset($post['role']['description']))
	            $role->descrip = $post['role']['description'];

	        $role->id = $this->_resource->put_record($role);
		}

        return ['resource' => 'role', 'id' => $role->id];
    }

    public function add_permission_action($get, $post) {
        $role_id = get_resource_id();

        if (isset($post['permission']['resource'], $post['permission']['action'])) {
			$permission = $this->get_permission(
				$post['permission']['resource'],
				$post['permission']['action']
			);

			$this->_resource->add_permission($role_id, $permission->id);
        }

        return ['resource' => 'role', 'id' => $role_id];
    }

    public function remove_permission_action($get, $post) {
        $role_id = get_resource_id();

        if (isset($post['permission']))
			$this->_resource->remove_permission($role_id, $post['permission']);

        return ['resource' => 'role', 'id' => $role_id];
    }

    public function index_view($vars) {
        $roles = $this->get_result();
		$roles->walk(function(&$role) {
            $role->permissions = resource::load('permission')->get_by_role_id($role->id);
		});

		$vars['roles'] = $roles;

        return $vars;
    }

    public function item_view($vars) {
        if ($role_id = get_resource_id())
            $vars['role'] = $this->_resource->get_record($role_id, ['permissions']);

        return $vars;
    }

    public function form_meta_view($vars) {
        if ($role_id = get_resource_id())
			$vars['role'] = $this->_resource->get_record($role_id);
		else
			$vars['role'] = new object();

		return $vars;
    }

    public function form_permission_view($vars) {
		if ($role_id = get_resource_id())
	        $vars['role'] = $this->_resource->get_record($role_id);

		$vars['resources'] = init_url()->resources;

        return $vars;
    }

    public function card_permissions_view($vars) {
        if ($role_id = get_resource_id())
            $vars['role'] = $this->_resource->get_record($role_id, 'permissions');

        return $vars;
    }

	protected function get_permission($resource, $action) {
		$resource_object = resource::load('permission');

		$permissions = $resource_object->get_by_resource_action($resource, $action);

		if ($permissions->found) {
			$permission = $permissions->first;
		} else {
			$permission = new object();
			$permission->resource = $post['permission']['resource'];
			$permission->action   = $post['permission']['action'];
			$permission->id       = $resource_object->put_record($permission);
		}

		return $permission;
	}
}
