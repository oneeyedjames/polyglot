<?php

use PHPunk\Database\query as database_query;

function get_projects($limit = DEFAULT_PER_PAGE, $offset = 0) {
	return get_records('project', $limit, $offset);
}

function get_languages($limit = DEFAULT_PER_PAGE, $offset = 0) {
	return get_records('language', $limit, $offset);
}

function get_records($resource, $limit, $offset) {
	$database = init_database();

	$query = new database_query($database, [
		'table'  => $resource,
		'limit'  => $limit,
		'offset' => $offset
	]);

	return $query->get_result();
}

function get_user($user_id) {
	$database = init_database();

	if ($record = $database->get_record('user', $user_id)) {
		$query = new database_query($database, [
			'table'  => 'role',
			'limit'  => 1,
			'bridge' => 'up_role',
			'args'   => [
				'up_user'    => $user_id,
				'up_project' => get_project_id()
			]
		]);

		if ($role = $query->get_result()->first) {
			$query = new database_query($database, [
				'table'  => 'permission',
				'bridge' => 'rp_permission',
				'args'   => [
					'rp_role'  => $role->id,
					'action'   => $action,
					'resource' => $resource
				]
			]);

			$perms = $query->get_result()->key_map(function($perm) {
				return $perm->id;
			});

			$query = new database_query($database, [
				'table' => 'role_permission_map',
				'args'  => [
					'role_id' => $role->id
				]
			]);

			$mappings = $query->get_result();

			foreach ($mappings as $mapping)
				$perms[$mapping->permission_id]->override = boolval($mapping->override);

			$role->permissions = $perms;
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
	$database = init_database();

	$sql = "SELECT * FROM session WHERE token = ?";

	if ($records = $database->query($sql, $token))
		return $records->first;

	return false;
}
