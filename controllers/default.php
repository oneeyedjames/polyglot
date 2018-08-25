<?php

class default_controller extends controller {
	public function __construct() {
		parent::__construct(resource::load('user'));
	}

	public function __get($key) {
		switch ($key) {
			case 'resource':
				return false;
			default:
				return parent::__get($key);
		}
	}

	protected function is_authorized($action, $resource = false) {
		if (in_array($action, ['login', 'logout', 'reset-password']))
			return true;

		return parent::is_authorized($action);
	}



	public function login_action($get, $post) {
		extract(@$post['login'], EXTR_SKIP);

		if ($record = $this->_resource->get_by_email($email)) {
			if (password_verify($password, $record->password)) {
				$user = new user($record);
				$token = $user->create_token();
				$expire = time() + (86400 * 7);

				if ($this->_resource->create_session($user->id, $token, $expire))
					setcookie('user_token', $token, $expire);

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
		$vars['projects']  = resource::load('project')->get_by_user_id(SESSION_USER_ID);
		$vars['languages'] = resource::load('language')->get_by_user_id(SESSION_USER_ID);

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
