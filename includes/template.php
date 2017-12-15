<?php

class template extends template_base {
	private $_controllers = [];

	public function __call($func, $args) {
		if ($count = count($this->_controllers)) {
			$controller = $this->_controllers[$count - 1];

			if (method_exists($controller, $func))
				return call_user_func_array([$controller, $func], $args);
		}

		trigger_error("Call to undefined method template::$func()", E_USER_WARNING);
	}

	public function load($view, $resource = false, $vars = []) {
		$granted = in_array($view, ['header', 'footer', 'login-form']);

		if (!$granted && $user = get_session_user())
			$granted = $user->has_permission($view, $resource);

		if ($granted) {
			$controller = $this->_controllers[] = controller::load($resource);
			$controller->pre_view($view, $vars);

			parent::load($view, $resource, $vars);

			array_pop($this->_controllers);
		}
	}

	public function pagination($item_count, $vars = []) {
		$vars['item_count'] = $item_count;

		$this->load('pagination', false, $vars);
	}
}

function disabled($actual, $expected = null) {
	if (is_null($expected) ? $actual : $actual == $expected)
		echo ' disabled="disabled"';
}

function selected($actual, $expected = null) {
	if (is_null($expected) ? $actual : $actual == $expected)
		echo ' selected="selected"';
}

function checked($actual, $expected = null) {
	if (is_null($expected) ? $actual : $actual == $expected)
		echo ' checked="checked"';
}

function page_url($params, $page) {
	$params['page'] = $page;

	return build_url($params);
}

function per_page_url($params, $per_page) {
	$params['per_page'] = $per_page;
	unset($params['page']);

	return build_url($params);
}

function build_url($url_params) {
	global $url_schema;
	return $url_schema->build($url_params);
}
