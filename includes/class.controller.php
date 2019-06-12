<?php

use PHPunk\Component\controller as controller_base;

class controller extends controller_base {
	private static $_default_controller = false;

	private static $_controllers = [];

	public static function load($resource = false) {
		if ($resource) {
			if (!isset(self::$_controllers[$resource])) {
				$class = "{$resource}_controller";

				if (!class_exists($class))
					$class = 'controller';

				$model = model::load($resource);

				self::$_controllers[$resource] = new $class($model);
			}

			return self::$_controllers[$resource];
		} else {
			if (!self::$_default_controller)
				self::$_default_controller = new default_controller(model::load());

			return self::$_default_controller;
		}
	}



	public function __call($func, $args) {
		if (method_exists($this->_model, $func))
			return call_user_func_array([$this->_model, $func], $args);

		trigger_error("Call to undefined method controller::$func()", E_USER_WARNING);
	}



	public function do_action($action) {
		if ($this->is_authorized($action))
			return parent::do_action($action);

		http_response_code(401);
	}

	public function delete_action($get, $post) {
		if ($id = get_resource_id())
			$this->remove_record($id);

		return ['resource' => $this->resource];
	}



	public function page_limit_view($vars) {
		if (!isset($vars['per_page']))
			$vars['per_page'] = get_per_page();

		if (!isset($vars['url_params']))
			$vars['url_params'] = $_GET;

		return $vars;
	}

	public function pagination_view($vars) {
		if (!isset($vars['page']))
			$vars['page'] = get_page();

		if (!isset($vars['per_page']))
			$vars['per_page'] = get_per_page();

		if (!isset($vars['page_range']))
			$vars['page_range'] = 2;

		if (!isset($vars['url_params']))
			$vars['url_params'] = $_GET;

		return $vars;
	}

	public function sorting_view($vars) {
		// TODO get access to resource-specific controller

		if (!($sort = get_sorting()))
			$sort = ['title' => 'asc'];

		if ($sort) {
			$key   = key($sort);
			$order = current($sort);

			if (!isset($vars['key']))
				$vars['key'] = $key;

			if ($vars['key'] == $key) {
				$vars['order'] = $order == 'asc' ? 'desc' : 'asc';
				$vars['dir']   = $order == 'asc' ? 'up'   : 'down';
			}

			if (!isset($vars['order']))
				$vars['order'] = $order;
		}

		if (!isset($vars['url_params']))
			$vars['url_params'] = $_GET;

		return $vars;
	}



	protected function is_authorized($action, $resource = false) {
		$resource = $resource ?: $this->resource;

		if ($user = get_session_user()) {
			if (!$user->verify_action_token(@$_POST['nonce'], $action, $resource))
				return false;

			if ($user->admin)
				return true;

			if ($permission = $user->get_permission($action, $resource)) {
				if ($resource_id = get_resource_id()) {
					if ($permission->override)
						return true;

					$record = model::load($resource)->get_record($resource_id);

					return $record->user_id == $user->id;
				} else {
					return true;
				}
			}
		}

		return false;
	}

	public function create_nonce($action, $resource = false) {
		$user = get_session_user();

		return $user->create_action_token($action, $resource ?: $this->resource);
	}

	public function build_url($params = [], $resource = false) {
		if (is_scalar($params) && is_numeric($params))
			$params = ['id' => intval($params)];

		$params['resource'] = $resource ?: $this->resource;

		return build_url($params);
	}

	public function redirect($url) {
		if (headers_sent())
			die("<script type=\"text/javascript\">window.location = '$url';</script>");
		else
			die(header("Location: $url"));
	}



	// TODO backport to PHPunk
	public function pre_render($view, &$result) {
		$method = 'api_' . str_replace('-', '_', $view) . '_view';
		if (method_exists($this, $method)) {
			$result = call_user_func([$this, $method], $_GET, $_POST);
		} else {
			$result = new api_error('api_undefined_view',
				'The requested API view is not defined', [
					'status'   => 400,
					'resource' => $this->resource,
					'view'     => $view
				]
			);
		}
	}

	public function api_index_view() {
		return $this->get_result();
	}

	public function api_item_view() {
		if ($record_id = get_resource_id()) {
			if ($record = $this->get_record($record_id))
				return $record;

			return new api_error('api_record_not_found',
				'The specified record could not be found', [
					'status'   => 404,
					'resource' => $this->resource,
					'id'       => get_resource_id()
				]
			);
		}

		return new api_error('api_record_id_not_specified',
			'No record ID was specified', [
				'status'   => 400,
				'resource' => $this->resource
			]
		);
	}
}
