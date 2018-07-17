<?php

// Ignore automatic browser requests for favicon
if ('/favicon.ico' == $_SERVER['REQUEST_URI']) {
	http_response_code(404);
	exit;
}

ini_set('display_errors', 1);
ini_set('default_charset', 'UTF-8');



// Load necessary dependencies
require_once 'vendor/autoload.php';

if (!function_exists('require_all')) {
	function require_all($pattern) {
		foreach (glob($pattern) as $filepath)
			require_once $filepath;
	}
}

require_all('includes/trait.*.php');
require_all('includes/class.*.php');
require_all('includes/*.php');
require_all('controllers/*.php');



// Bootstrap application components

if (!($database = init_database()))
	error_page(500);

if (!($cache = init_cache()))
	error_page(500);

controller::init($database, $cache);



// Verify user authentication and session status
define('IS_LOGIN', 'login' == get_action() || 'login-form' == get_view());
define('SESSION_USER_ID', init_session());



// Route back-end requests

if ($action = get_action()) {
	$params = controller::load(get_resource())->do_action($action);

	if (is_array($params))
		header('Location: ' . build_url($params));

	exit;
}



// Render requested view

if (is_api()) {
	controller::load(get_resource())->api_view();
} else {
	$template = new template(TEMPLATE_PATH);

	if (!is_ajax())
		$template->load('header');

	if ($resource = get_resource()) {
		if (!($view = get_view()))
			$view = get_resource_id() ? 'item' : 'index';

		$template->load($view, $resource);
	} else {
		$template->load(get_view() ?: 'index');
	}

	if (!is_ajax())
		$template->load('footer');
}
