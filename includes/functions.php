<?php

function get_projects($limit = DEFAULT_PER_PAGE, $offset = 0) {
	return get_records('project', $limit, $offset);
}

function get_languages($limit = DEFAULT_PER_PAGE, $offset = 0) {
	return get_records('language', $limit, $offset);
}

function get_records($resource, $limit, $offset) {
	global $database;

	$query = new database_query($database, array(
		'table' => $resource,
		'limit' => $limit,
		'offset' => $offset
	));

	return $query->get_result();
}

function get_user($user_id) {
	global $database;

	if ($record = $database->get_record('user', $user_id)) {
		$query = new database_query($database, array(
			'table'  => 'role',
			'limit'  => 1,
			'bridge' => 'up_role',
			'args' => array(
				'up_user'    => $user_id,
				'up_project' => get_project_id()
			)
		));

		if ($role = $query->get_result()->first) {
			$query = new database_query($database, array(
				'table'  => 'permission',
				'bridge' => 'rp_permission',
				'args'   => array(
					'rp_role'  => $role->id,
					'action'   => $action,
					'resource' => $resource
				)
			));

			$role->permissions = $query->get_result();
		}

		$user = new user($record, $role);

		return $user;
	}

	return false;
}

function get_session_user() {
	if (!defined('SESSION_USER_ID'))
		return false;

	return get_user(SESSION_USER_ID);
}

function get_session($token) {
	global $database;

	$sql = "SELECT * FROM session WHERE token = ?";

	if ($records = $database->query($sql, $token))
		return $records->first;

	return false;
}
