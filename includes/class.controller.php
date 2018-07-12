<?php

class controller extends controller_base {
	use password;

	private static $_default_controller = false;
	private static $_default_database   = false;
	private static $_default_cache      = false;

	private static $_controllers = [];

	public static function init($database = false, $cache = false) {
		if (!self::$_default_database)
			self::$_default_database = $database;

		if (!self::$_default_cache)
			self::$_default_cache = $cache;
	}

	public static function load($resource = false) {
		$database = self::$_default_database;
		$cache    = self::$_default_cache;

		if (!$database)
			trigger_error("No default database", E_USER_ERROR);

		if ($resource) {
			if (!isset(self::$_controllers[$resource])) {
				$class = "{$resource}_controller";

				if (class_exists($class))
					self::$_controllers[$resource] = new $class($database, $cache);
				else
					self::$_controllers[$resource] = new self($resource, $database, $cache);
			}

			return self::$_controllers[$resource];
		} else {
			if (!self::$_default_controller)
				self::$_default_controller = new self(false, $database, $cache);

			return self::$_default_controller;
		}
	}

	public function do_action($action) {
		if (in_array($action, ['login', 'logout', 'reset-password']))
			return parent::do_action($action);

		if ($this->is_authorized($action))
			return parent::do_action($action);

		http_response_code(401);
	}

	public function login_action($get, $post) {
		extract(@$post['login'], EXTR_SKIP);

		$result = $this->make_query([
			'args'  => compact('email'),
			'limit' => 1
		], 'user')->get_result();

		if ($record = $result->first) {
			if (password_verify($password, $record->password)) {
				$user = new user($record);
				$token = $user->create_token();
				$expire = time() + (86400 * 7);

				setcookie('user_token', $token, $expire);

				$sql = 'INSERT INTO session (user_id, token, expire) VALUES (?, ?, ?)';

				$this->execute($sql, intval($user->id), $token, date('Y-m-d H:i:s', $expire));

				return ['view' => 'home'];
			}
		}

		return $this->logout_action($get, $post);
	}

	public function logout_action($get, $post) {
		setcookie('user_token', null, time() - 300);

		return ['view', 'login-form'];
	}

	public function reset_password_action($get, $post) {
		if (isset($post['email'])) {
			$this->create_reset_token($post['email']);
		} elseif (isset($post['token'], $post['login']['password'], $post['login']['password-confirm'])) {
			$this->reset_password($post['token'], $post['login']['password'],
				$post['login']['password-confirm']);

			return ['view' => 'login-form'];
		}

		return [];
	}

	public function delete_action($get, $post) {
		if ($id = get_resource_id())
			$this->remove_record($id);

		return ['resource' => $this->resource];
	}

	public function index_view($vars) {
		$vars['projects'] = $this->make_query([
			'limit' => 3
		], 'project')->get_result();

		$vars['projects']->walk(function(&$project) {
			$project->languages = $this->make_query([
				'bridge' => 'pl_language',
				'limit'  => 3,
				'args'   => [
					'pl_project' => $project->id
				]
			], 'language')->get_result();

			$project->users = $this->make_query([
				'bridge' => 'up_user',
				'limit'  => 3,
				'args'   => [
					'up_project' => $project->id
				]
			], 'user')->get_result();
		});

		$vars['languages'] = $this->make_query([
			'limit' => 3
		], 'language')->get_result();

		$vars['languages']->walk(function(&$language) {
			$language->projects = $this->make_query([
				'bridge' => 'pl_project',
				'limit'  => 3,
				'args'   => [
					'pl_language' => $language->id
				]
			], 'project')->get_result();

			$language->users = $this->make_query([
				'bridge' => 'ul_user',
				'limit'  => 3,
				'args'   => [
					'ul_language' => $language->id
				]
			], 'user')->get_result();
		});

		$vars['users'] = $this->make_query([
			'limit' => 3
		], 'user')->get_result();

		$vars['users']->walk(function(&$user) {
			$user->projects = $this->make_query([
				'bridge' => 'up_project',
				'limit'  => 3,
				'args'   => [
					'up_user' => $user->id
				]
			], 'project')->get_result();

			$user->languages = $this->make_query([
				'bridge' => 'ul_language',
				'limit'  => 3,
				'args'   => [
					'ul_user' => $user->id
				]
			], 'language')->get_result();
		});

		return $vars;
	}

	public function reset_password_form_view($vars) {
		if ($token = get_filter('token')) {
			$user = $this->make_query([
				'limit' => 1,
				'args'  => [
					'reset_token' => $token
				]
			], 'user')->get_result()->first;

			if ($user) {
				if (strtotime($user->reset_expire) > time()) {
					$vars['user'] = $user;
				} else {
					$vars['error'] = "Expired token.";

					$user->reset_token  = null;
					$user->reset_expire = null;

					$this->put_record($user, 'user');
				}

				$vars['token'] = $token;
			} else {
				$vars['error'] = "Invalid token.";
			}
		} else {
			$vars['error'] = "Invalid token.";
		}

		return $vars;
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

					$record = $this->get_record($resource_id);

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

	public function api_view() {
		if (!($view = get_view()))
			$view = get_resource_id() ? 'item' : 'index';

		$method = 'api_' . str_replace('-', '_', $view) . '_view';

		if (method_exists($this, $method)) {
			$record = call_user_func(array($this, $method));
		} else {
			$record = $this->api_error('api_undefined_view', 'The requested API view is not defined', [
				'status'   => 400,
				'resource' => $this->resource,
				'view'     => $view
			]);
		}

		if (isset($record['data']['status'])) {
			http_response_code($record['data']['status']);
			unset($record['data']['status']);
		}

		header('Content-type: text/json');
		echo json_encode($record);
	}

	protected function api_error($code, $message, $data = []) {
		if (!is_array($data)) $data = [];
		return compact('code', 'message', 'data');
	}

	public function add_links(&$record) {
		$record['_links'] = [
			[
				'rel'  => 'self',
				'href' => "/api/$this->resource/$record->id",
			],
			[
				'rel'  => 'collection',
				'href' => "/api/$this->resource",
			]
		];

		return $record;
	}

	public function redirect($url) {
		if (headers_sent())
			die("<script type=\"text/javascript\">window.location = '$url'</script>");
		else
			die(header("Location: $url"));
	}
}