<?php

class user_controller extends controller {
	public function __construct($database, $cache = null) {
		parent::__construct('user', $database, $cache);
	}

	public function save_action($get, $post) {
		$user = new object();
		$user->id = get_resource_id();

		return ['resource' => 'user', 'id' => $user->id];
	}

	public function add_language_action($get, $post) {
		if ($user_id = get_resource_id()) {
			if (isset($post['language'])) {
				$this->put_record(new object([
					'user_id' => $user_id,
					'language_id' => intval($post['language'])
				]), 'user_language_map');
			}

			return ['resource' => 'user', 'id' => $user_id];
		}

		return ['resource' => 'users'];
	}

	public function remove_language_action($get, $post) {
		if ($user_id = get_resource_id()) {
			if (isset($post['language'])) {
				$sql = 'DELETE FROM `user_language_map` WHERE `user_id` = ? AND `language_id` = ?';
				$this->execute($sql, $user_id, intval($post['language']));
			}

			return ['resource' => 'user', 'id' => $user_id];
		}

		return ['resource' => 'users'];
	}

	public function add_project_action($get, $post) {
		if ($user_id = get_resource_id()) {
			if (isset($post['project'], $post['role'])) {
				$this->put_record(new object([
					'user_id' => $user_id,
					'project_id' => intval($post['project']),
					'role_id' => intval($post['role'])
				]), 'user_project_map');
			}

			return ['resource' => 'user', 'id' => $user_id];
		}

		return ['resource' => 'users'];
	}

	public function remove_project_action($get, $post) {
		if ($user_id = get_resource_id()) {
			if (isset($post['project'])) {
				$sql = 'DELETE FROM `user_project_map` WHERE `user_id` = ? AND `project_id` = ?';
				$this->execute($sql, $user_id, intval($post['project']));
			}

			return ['resource' => 'user', 'id' => $user_id];
		}

		return ['resource' => 'users'];
	}

	public function index_view($vars) {
		$limit  = get_per_page();
		$offset = get_offset(get_page(), $limit);

		$args = compact('limit', 'offset');

		$users = $this->make_query($args)->get_result();
		$users->walk(function(&$user) {
			$user->languages = $this->get_languages($user->id);
			$user->projects  = $this->get_projects($user->id);
		});

		$vars['users'] = $users;

		return $vars;
	}

	public function item_view($vars) {
		if ($user_id = get_resource_id()) {
			$user = $this->get_record($user_id);
			$user->languages = $this->get_languages($user_id);
			$user->projects  = $this->get_projects($user_id);

			$vars['user'] = $user;
		}

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
		if ($user_id = get_resource_id())
			$vars['user'] = $this->get_record($user_id);
		else
			$vars['user'] = new object();

		$vars['projects'] = $this->make_query([], 'project')->get_result();
		$vars['roles'] = $this->make_query([], 'role')->get_result();

		return $vars;
	}

	public function form_language_view($vars) {
		if ($user_id = get_resource_id())
			$vars['user'] = $this->get_record($user_id);
		else
			$vars['user'] = new object();

		$vars['languages'] = $this->make_query([], 'language')->get_result();

		return $vars;
	}

	public function card_projects_view($vars) {
		if ($user_id = get_resource_id()) {
			$user = $this->get_record($user_id);
			$user->projects = $this->make_query([
				'bridge' => 'up_project',
				'limit'  => 3,
				'args'   => [
					'up_user' => $user_id
				]
			], 'project')->get_result();

			$vars['user'] = $user;
		}

		return $vars;
	}

	public function card_languages_view($vars) {
		if ($user_id = get_resource_id()) {
			$user = $this->get_record($user_id);
			$user->languages = $this->make_query([
				'bridge' => 'ul_language',
				'limit'  => 3,
				'args'   => [
					'ul_user' => $user_id
				]
			], 'language')->get_result();

			$vars['user'] = $user;
		}

		return $vars;
	}

	protected function get_languages($user_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['bridge'] = 'ul_language';
		$args['args'] = ['ul_user' => $user->id];

		return $this->make_query($args, 'language')->get_result();
	}

	protected function get_projects($user_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['bridge'] = 'up_project';
		$args['args'] = ['up_user' => $user->id];

		$projects = $this->make_query($args, 'project')->get_result();
		$projects->walk(function(&$project) {
			$project->role = $this->make_query([
				'bridge' => 'up_role',
				'args'   => [
					'up_user'    => $user_id,
					'up_project' => $project->id
				]
			], 'role')->get_result()->first;
		});

		return $projects;
	}
}
