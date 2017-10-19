<?php

function get_resource() {
	global $url_schema;
	return $url_schema->is_resource(@$_GET['resource']);
}

function get_resource_id() {
	return is_numeric(@$_GET['id']) ? intval($_GET['id']) : false;
}

function get_action() {
	global $url_schema;
	return $url_schema->is_action(@$_GET['action'], @$_GET['resource']);
}

function get_view() {
	global $url_schema;
	return $url_schema->is_view(@$_GET['view'], @$_GET['resource']);
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
