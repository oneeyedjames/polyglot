<?php

class role_controller extends controller {
	public function save_action($get, $post) {
		$role = $this->create_record(get_resource_id());

		if (isset($post['role'])) {
			if (isset($post['role']['title']))
				$role->title = $post['role']['title'];

			if (isset($post['role']['description']))
				$role->descrip = $post['role']['description'];

			$role->id = $this->put_record($role);
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

			$override = 'true' == @$post['permission']['override'];

			$this->add_permission($role_id, $permission->id, $override);
		}

		return ['resource' => 'role', 'id' => $role_id];
	}

	public function remove_permission_action($get, $post) {
		$role_id = get_resource_id();

		if (isset($post['permission']))
			$this->remove_permission($role_id, $post['permission']);

		return ['resource' => 'role', 'id' => $role_id];
	}

	public function index_view($vars) {
		$vars['roles'] = $this->get_result([], ['permissions']);

		return $vars;
	}

	public function item_view($vars) {
		if ($role_id = get_resource_id())
			$vars['role'] = $this->get_record($role_id, ['permissions']);

		return $vars;
	}

	public function form_meta_view($vars) {
		if ($role_id = get_resource_id())
			$vars['role'] = $this->get_record($role_id);
		else
			$vars['role'] = $this->create_record();

		return $vars;
	}

	public function form_permission_view($vars) {
		if ($role_id = get_resource_id())
			$vars['role'] = $this->get_record($role_id);

		$vars['resources'] = init_url()->resources;

		return $vars;
	}

	public function card_permissions_view($vars) {
		if ($role_id = get_resource_id())
			$vars['role'] = $this->get_record($role_id, 'permissions');

		return $vars;
	}

	protected function get_permission($resource, $action) {
		$permissions = model::load('permission')->get_by_resource_action($resource, $action);

		return $permissions->found ? $permissions->first : $this->create_permission($resource, $action);
	}

	protected function create_permission($resource, $action) {
		$model = model::load('permission');

		$permission = $model->create_record(compact('resource', 'action'));
		$permission->id = $model->put_record($permission);

		return $permission;
	}
}
