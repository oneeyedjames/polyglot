<?php

function get_resource() {
	return init_url()->is_resource(@$_GET['resource']);
}

function get_resource_id() {
	return is_numeric(@$_GET['id']) ? intval($_GET['id']) : false;
}

function get_project_id() {
	$database = init_database();

	$resource = get_resource();
	$resource_id = get_resource_id();

	switch ($resource) {
		case 'project':
			return get_resource_id();
		case 'document':
		case 'list':
			$record = $database->get_record($resource, $resource_id);
			return $record ? $record->project_id : get_filter('project');
		case 'term':
			$term = $database->get_record($resource, $resource_id);
			$list_id = $term ? $term->list_id : get_filter('list');

			if ($list_id && $list = $database->get_record('list', $list_id))
				return $list->project_id;

			return false;
		default:
			return false;
	}
}

function get_action() {
	return init_url()->is_action(@$_GET['action'], @$_GET['resource']);
}

function get_view() {
	return init_url()->is_view(@$_GET['view'], @$_GET['resource']);
}

/**
 * Default is page 1, page count is 1-based
 */
function get_page() {
	return is_numeric(@$_GET['page']) ? intval($_GET['page']) : DEFAULT_PAGE;
}

/**
 * Default is 12 items per page
 */
function get_per_page() {
	return is_numeric(@$_GET['per_page']) ? intval($_GET['per_page']) : DEFAULT_PER_PAGE;
}

function get_offset($page, $per_page) {
	return ($page - 1) * $per_page;
}

function get_filter($key) {
	return isset($_GET['filter'][$key]) ? $_GET['filter'][$key] : false;
}

function is_api() {
	return boolval(@$_GET['api']);
}

function is_ajax() {
	return boolval(@$_GET['ajax']);
}
