<?php

class controller extends controller_base {
	private static $_default_controller = false;
	private static $_default_database   = false;
	private static $_default_cache      = false;

	private static $_controllers = array();

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
		if (in_array($action, array('login', 'logout')))
			return parent::do_action($action);

		if ($this->is_authorized($action))
			return parent::do_action($action);

		http_response_code(401);
	}

	public function login_action($get, $post) {
		extract(@$post['login'], EXTR_SKIP);

		$result = $this->make_query(array(
			'args'  => compact('email'),
			'limit' => 1
		), 'user')->get_result();

		if ($record = $result->first) {
			if (password_verify($password, $record->password)) {
				$user = new user($record);
				$token = $user->create_token();
				$expire = time() + (86400 * 7);

				setcookie('user_token', $token, $expire);

				$sql = 'INSERT INTO session (user_id, token, expire) VALUES (?, ?, ?)';

				$this->execute($sql, intval($user->id), $token, date('Y-m-d H:i:s', $expire));

				return array('view' => 'home');
			}
		}

		return $this->logout_action($get, $post);
	}

	public function logout_action($get, $post) {
		setcookie('user_token', null, time() - 300);

		return array('view', 'login-form');
	}

	public function index_view($vars) {
		$vars['projects'] = $this->make_query(array(
			'limit' => 3
		), 'project')->get_result();
		$vars['projects']->walk(function(&$project) {
			$project->languages = $this->make_query(array(
				'bridge' => 'pl_language',
				'limit'  => 3,
				'args'   => array(
					'pl_project' => $project->id
				)
			), 'language')->get_result();

			$project->users = $this->make_query(array(
				'bridge' => 'up_user',
				'limit'  => 3,
				'args'   => array(
					'up_project' => $project->id
				)
			), 'user')->get_result();
		});

		$vars['languages'] = $this->make_query(array(
			'limit' => 3
		), 'language')->get_result();
		$vars['languages']->walk(function(&$language) {
			$language->projects = $this->make_query(array(
				'bridge' => 'pl_project',
				'limit'  => 3,
				'args'   => array(
					'pl_language' => $language->id
				)
			), 'project')->get_result();

			$language->users = $this->make_query(array(
				'bridge' => 'ul_user',
				'limit'  => 3,
				'args'   => array(
					'ul_language' => $language->id
				)
			), 'user')->get_result();
		});

		$vars['users'] = $this->make_query(array(
			'limit' => 3
		), 'user')->get_result();
		$vars['users']->walk(function(&$user) {
			$user->projects = $this->make_query(array(
				'bridge' => 'up_project',
				'limit'  => 3,
				'args'   => array(
					'up_user' => $user->id
				)
			), 'project')->get_result();

			$user->languages = $this->make_query(array(
				'bridge' => 'ul_language',
				'limit'  => 3,
				'args'   => array(
					'ul_user' => $user->id
				)
			), 'language')->get_result();
		});

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

			return $user->has_permission($action, $resource);
		}

		return false;
	}

	public function create_nonce($action, $resource = false) {
		$user = get_session_user();

		return $user->create_action_token($action, $resource ?: $this->resource);
	}

	public function build_url($params = array(), $resource = false) {
		if (is_scalar($params) && is_numeric($params))
			$params = array('id' => intval($params));

		$params['resource'] = $resource ?: $this->resource;

		return build_url($params);
	}

	public function api_view() {
		http_response_code(404);
		die('{"error":"This endpoint is not yet implemented"}');
	}

	public function redirect($url) {
		if (headers_sent())
			die("<script type=\"text/javascript\">window.location = '$url'</script>");
		else
			die(header("Location: $url"));
	}
}
