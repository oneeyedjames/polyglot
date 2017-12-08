<?php

trait authorization {
    protected abstract function make_query($args, $resource = false);
    protected abstract function get_record($id, $resource = false);

    protected function is_authorized($action, $resource) {
		if ($user = get_session_user()) {
			if ($user->admin)
				return true;

			if ($role = $this->get_role($user->id, get_project_id())) {
				foreach ($role->permissions as $permission) {
					if ($permission->action == $action && $permission->resource == $resource)
						return true;
				}
			}
		}

		return false;
	}

    protected function get_role($user_id = SESSION_USER_ID, $proj_id = 0) {
		$role = $this->make_query(array(
			'limit'  => 1,
			'bridge' => 'up_role',
			'args' => array(
				'up_user'    => $user_id,
				'up_project' => $proj_id
			)
		), 'role')->get_result()->first;

		$role->permissions = $this->make_query(array(
			'bridge' => 'rp_permission',
			'args'   => array(
				'rp_role'  => $role->id,
				'action'   => $action,
				'resource' => $resource
			)
		), 'permission')->get_result();

		return $role;
	}
}
