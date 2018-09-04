<?php

class user_controller extends controller {
	public function save_action($get, $post) {
		$user = new object();
		$user->id = get_resource_id();

		if (isset($post['user']['name']))
			$user->name = $post['user']['name'];

		if (isset($post['user']['alias']))
			$user->alias = $post['user']['alias'];

		if (isset($post['user']['email']))
			$user->email = $post['user']['email'];

		if (isset($post['user']['admin']))
			$user->admin = boolval($post['user']['admin']);

		if (!$user->id) {
			if (empty($user->alias))
				$user->alias = $user->name;

			// Create random password
			$user->password = password_hash(create_nonce(16), PASSWORD_DEFAULT);

			// Create reset token for 30 minutes
			$user->reset_token  = create_nonce(16);
			$user->reset_expire = date('Y-m-d H:i:s', time() + 1800);

			if (!empty($user->name) && !empty($user->email))
				$this->put_record($user);
		} else {
			$this->put_record($user);
		}

		return ['resource' => 'user', 'id' => $user->id];
	}

	public function add_project_action($get, $post) {
		$user_id = get_resource_id();

		if (isset($post['project'], $post['role']))
			$this->add_project($user_id, $post['project'], $post['role']);

		return ['resource' => 'user', 'id' => $user_id];
	}

	public function remove_project_action($get, $post) {
		$user_id = get_resource_id();

		if (isset($post['project']))
			$this->remove_project($user_id, $post['project']);

		return ['resource' => 'users'];
	}

	public function add_language_action($get, $post) {
		$user_id = get_resource_id();

		if (isset($post['language']))
			$this->add_language($user_id, $post['language']);

		return ['resource' => 'user', 'id' => $user_id];
	}

	public function remove_language_action($get, $post) {
		$user_id = get_resource_id();

		if (isset($post['language']))
			$this->remove_language($user_id, $post['language']);

		return ['resource' => 'users'];
	}

	public function index_view($vars) {
		$vars['users'] = $this->get_result([], ['projects', 'languages']);

		return $vars;
	}

	public function item_view($vars) {
		if ($user_id = get_resource_id())
			$vars['user'] = $this->get_record($user_id, ['projects', 'languages']);

		return $vars;
	}

	public function form_meta_view($vars) {
		if ($user_id = get_resource_id())
			$vars['user'] = $this->get_record($user_id);
		else
			$vars['user'] = new object();

		return $vars;
	}

	public function form_project_view($vars) {
		if ($user_id = get_resource_id()) {
			$vars['user']     = $this->get_record($user_id);
			$vars['projects'] = $this->get_projects();
			$vars['roles']    = $this->get_roles();
		}

		return $vars;
	}

	public function form_language_view($vars) {
		if ($user_id = get_resource_id()) {
			$vars['user']      = $this->get_record($user_id);
			$vars['languages'] = $this->get_languages();
		}

		return $vars;
	}

	public function card_projects_view($vars) {
		if ($user_id = get_resource_id())
			$vars['user'] = $this->get_record($user_id, ['projects']);

		return $vars;
	}

	public function card_languages_view($vars) {
		if ($user_id = get_resource_id())
			$vars['user'] = $this->get_record($user_id, ['languages']);

		return $vars;
	}

	protected function get_projects() {
		return resource::load('project')->get_all();
	}

	protected function get_languages() {
		return resource::load('language')->get_all();
	}

	protected function get_roles() {
		return resource::load('role')->get_all();
	}
}
