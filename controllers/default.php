<?php

class default_controller extends controller {
	public function __construct() {
		parent::__construct(resource::load('user'));
	}

	public function __get($key) {
		switch ($key) {
			case 'resource':
				return 'default';
			default:
				return parent::__get($key);
		}
	}



	public function do_action($action) {
		if (in_array($action, ['login', 'logout', 'reset-password']))
			return parent::do_action($action);

		return parent::do_action($action);
	}

	public function login_action($get, $post) {
		extract(@$post['login'], EXTR_SKIP);

		$result = $this->_resource->make_query([
			'args'  => compact('email'),
			'limit' => 1
		])->get_result();

		if ($record = $result->first) {
			if (password_verify($password, $record->password)) {
				$user = new user($record);
				$token = $user->create_token();
				$expire = time() + (86400 * 7);

				setcookie('user_token', $token, $expire);

				$sql = 'INSERT INTO session (user_id, token, expire) VALUES (?, ?, ?)';

				$this->_resource->execute($sql, intval($user->id), $token, date('Y-m-d H:i:s', $expire));

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
		$resource = resource::load('user');

		if (isset($post['email'])) {
			$this->_resource->create_reset_token($post['email']);
		} elseif (isset($post['token'], $post['login']['password'], $post['login']['password-confirm'])) {
			$this->_resource->reset_password($post['token'], $post['login']['password'],
				$post['login']['password-confirm']);

			return ['view' => 'login-form'];
		}

		return [];
	}



	public function index_view($vars) {
		$vars['projects'] = resource::load('project')->make_query([
			'bridge' => 'up_project',
			'args'   => [
				'up_user' => SESSION_USER_ID
			]
		])->get_result();

		$vars['languages'] = resource::load('language')->make_query([
			'bridge' => 'ul_language',
			'args'   => [
				'ul_user' => SESSION_USER_ID
			]
		])->get_result();

		return $vars;
	}

	public function reset_password_form_view($vars) {
		if ($token = get_filter('token')) {
			$user = $this->_resource->make_query([
				'limit' => 1,
				'args'  => [
					'reset_token' => $token
				]
			])->get_result()->first;

			if ($user) {
				if (strtotime($user->reset_expire) > time()) {
					$vars['user'] = $user;
				} else {
					$vars['error'] = "Expired token.";

					$user->reset_token  = null;
					$user->reset_expire = null;

					$this->_resource->put_record($user);
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



	public function api_index_view() {
		return [];
	}
}
