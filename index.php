<?php

if ('/favicon.ico' == $_SERVER['REQUEST_URI']) {
	http_response_code(404);
	exit;
}


ini_set('display_errors', 1);
ini_set('default_charset', 'UTF-8');

require_once 'vendor/autoload.php';

foreach (glob('includes/*.php') as $filepath)
	require_once $filepath;

foreach (glob('controllers/*.php') as $filepath)
	require_once $filepath;

unset($filepath);

if (!($database = init_database()))
	error_page(500);

if (!($cache = init_cache()))
	error_page(500);

controller::init($database, $cache);

$url_schema = init_url();

define('IS_LOGIN', 'login' == get_action() || 'login-form' == get_view());
define('SESSION_USER_ID', init_session());



if ($action = get_action()) {
	$params = controller::load(get_resource())->do_action($action);

	if (is_array($params))
		header('Location: ' . $url_schema->build($params));

	exit;
} elseif (is_api()) {
	header('Content-type: text/json');
	controller::load(get_resource())->api_view();
	exit;
}



$template = new template(TEMPLATE_PATH);

if (!is_ajax())
	$template->load('header');

if ($resource = get_resource()) {
	$view = get_view();

	if (!$view)
		$view = get_resource_id() ? 'item' : 'index';

	$template->load($view, $resource);
} elseif ($view = $url_schema->is_view(@$_GET['view'])) {
	$template->load($view);
} else {
	$template->load('index');
}

if (!is_ajax())
	$template->load('footer');
