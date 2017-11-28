<?php

trait authorization {
    protected abstract function make_query($args, $resource = false);
    protected abstract function get_record($id, $resource = false);

    protected function is_authorized($action, $resource) {
		if ($user = get_session_user()) {
			if ($user->admin)
				return true;

			$proj_id = $this->get_project_id($resource);

			if ($role = $this->get_role($user->id, $proj_id)) {
				foreach ($role->permissions as $permission) {
					if ($permission->action == $action && $permission->resource == $resource)
						return true;
				}
			}
		}

		return false;
	}

    protected function get_project_id($resource) {
        $proj_id = 0;

        switch ($resource) {
            case 'project':
                $proj_id = get_resource_id();
                break;
            case 'document':
            case 'list':
                $record = $this->get_record(get_resource_id(), $resource);
                $proj_id = $record ? $record->project_id : get_filter('project');
                break;
            case 'term':
				$term = $this->get_record(get_resource_id(), 'term');
				$list_id = $term ? $term->list_id : get_filter('list');

				if ($list_id && $list = $this->get_record($list_id, 'list'))
					$proj_id = $list->project_id;

                break;
        }

        return $proj_id;
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
				'resource' => $resource,
				'granted'  => 1
			)
		), 'permission')->get_result();

		return $role;
	}
}
