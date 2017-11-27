<?php

class user_controller extends controller {
	public function __construct($database, $cache = null) {
		parent::__construct('user', $database, $cache);
	}

	public function save_action($get, $post) {
		$user = new object();
		$user->id = get_resource_id();

		if ($user->id && isset($post['projects'])) {
			$sql = 'DELETE FROM `user_project_map` WHERE `user_id` = ?';
			$this->execute($sql, $user->id);

			foreach ($post['projects'] as $proj_id => $role_id) {
				$this->put_record(array(
					'user_id'    => $user->id,
					'project_id' => intval($proj_id),
					'role_id'    => intval($role_id)
				), 'user_project_map');
			}
		}

		if ($user->id && isset($post['languages'])) {
			$sql = 'DELETE FROM `user_language_map` WHERE `user_id` = ?';
			$this->execute($sql, $user->id);

			foreach ($post['languages'] as $lang_id) {
				$record = new object(array(
					'user_id' => $user->id,
					'language_id' => intval($lang_id)
				));

				$this->put_record($record, 'user_language_map');
			}
		}

		return array('resource' => 'user', 'id' => $user->id);
	}

	public function index_view($vars) {
		$limit  = get_per_page();
		$offset = get_offset(get_page(), $limit);

		$args = compact('limit', 'offset');

		$users = $this->make_query($args)->get_result();
		$users->walk(function(&$user) {
			$user->projects = $this->make_query(array(
				'bridge' => 'up_project',
				'args'   => array(
					'up_user' => $user->id
				)
			), 'project')->get_result();

			$query = $this->make_query(array(
				'bridge' => 'ul_language',
				'args'   => array(
					'ul_user' => $user->id
				)
			), 'language');
			$query->get_result();

			$user->languages = $this->make_query(array(
				'bridge' => 'ul_language',
				'args'   => array(
					'ul_user' => $user->id
				)
			), 'language')->get_result();
		});

		$vars['users'] = $users;

		return $vars;
	}

	public function item_view($vars) {
		$vars['user'] = $this->get_record(get_resource_id());

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
		$user = $this->get_record(get_resource_id());

		$user->projects = $this->make_query(array(
			'bridge' => 'up_project',
			'args'   => array(
				'up_user' => $user->id
			)
		), 'project')->get_result()->key_map(function($project) {
			return $project->id;
		});

		$user->projects->walk(function(&$project) {
			$project->role = $this->make_query(array(
				'bridge' => 'up_role',
				'args'   => array(
					'up_user'    => $user->id,
					'up_project' => $project->id
				)
			), 'role')->get_result()->first;
		});

		$vars['user'] = $user;
		$vars['projects'] = $this->make_query(array(), 'project')->get_result();
		$vars['roles'] = $this->make_query(array(), 'role')->get_result();

		return $vars;
	}

	public function form_language_view($vars) {
		$user = $this->get_record(get_resource_id());
		$user->languages = $this->make_query(array(
			'bridge' => 'ul_language',
			'args'   => array(
				'ul_user' => $user->id
			)
		), 'language')->get_result()->key_map(function($language) {
			return $language->id;
		});

		$vars['user'] = $user;
		$vars['languages'] = $this->make_query(array(), 'language')->get_result();

		return $vars;
	}

	public function card_projects_view($vars) {
		if ($user_id = get_resource_id()) {
			$user = $this->get_record($user_id);
			$user->projects = $this->make_query(array(
				'bridge' => 'up_project',
				'limit'  => 3,
				'args'   => array(
					'up_user' => $user_id
				)
			), 'project')->get_result();

			$vars['user'] = $user;
		}

		return $vars;
	}

	public function card_languages_view($vars) {
		if ($user_id = get_resource_id()) {
			$user = $this->get_record($user_id);
			$user->languages = $this->make_query(array(
				'bridge' => 'ul_language',
				'limit'  => 3,
				'args'   => array(
					'ul_user' => $user_id
				)
			), 'language')->get_result();

			$vars['user'] = $user;
		}

		return $vars;
	}
}
