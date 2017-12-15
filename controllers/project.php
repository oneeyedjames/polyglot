<?php

class project_controller extends controller {
	public function __construct($database, $cache = null) {
		parent::__construct('project', $database, $cache);
	}

	public function save_action($get, $post) {
		$project = new object();
		$project->id = get_resource_id();

		if (isset($post['project'])) {
			if (isset($post['project']['language']))
				$project->default_language_id = intval($post['project']['language']);
			elseif ($lang_id = get_filter('language'))
				$project->default_language_id = intval($lang_id);

			if (isset($post['project']['title']))
				$project->title = $post['project']['title'];

			if (isset($post['project']['description']))
				$project->descrip = $post['project']['description'];

			$project->id = $this->put_record($project);
		}

		return ['resource' => 'project', 'id' => $project->id];
	}

	public function add_language_action($get, $post) {
		$project_id = get_resource_id();

		if (isset($post['language'])) {
			$this->put_record(new object([
				'project_id'  => $project_id,
				'language_id' => intval($post['language'])
			]), 'project_language_map');
		}

		return ['resource' => 'project', 'id' => $project_id];
	}

	public function remove_language_action($get, $post) {
		$project_id = get_resource_id();

		if (isset($post['language'])) {
			$sql = 'DELETE FROM `project_language_map` WHERE `project_id` = ? AND `language_id` = ?';
			$this->execute($sql, $project_id, intval($post['language']));
		}

		return ['resource' => 'project', 'id' => $project_id];
	}

	public function add_user_action($get, $post) {
		$project_id = get_resource_id();

		if (isset($post['user'], $post['role'])) {
			$record = new object([
				'project_id' => $project_id,
				'user_id'    => intval($post['user']),
				'role_id'    => intval($post['role'])
			]);

			$this->put_record($record, 'user_project_map');
		}

		return ['resource' => 'project', 'id' => $project_id];
	}

	public function remove_user_action($get, $post) {
		$project_id = get_resource_id();

		if (isset($post['user'])) {
			$sql = 'DELETE FROM `user_project_map` WHERE `project_id` = ? AND `user_id` = ?';
			$this->execute($sql, $project_id, intval($post['user']));
		}

		return ['resource' => 'project', 'id' => $project_id];
	}

	public function index_view($vars) {
		$limit  = get_per_page();
		$offset = get_offset(get_page(), $limit);

		$args = compact('limit', 'offset');

		$projects = $this->make_query($args)->get_result();
		$projects->walk([$this, 'fill_project']);

		$vars['projects'] = $projects;

		return $vars;
	}

	public function item_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->get_record($proj_id);

			$this->fill_project($project);

			$vars['project'] = $project;
		}

		return $vars;
	}

	public function form_meta_view($vars) {
		if ($proj_id = get_resource_id())
			$vars['project'] = $this->get_record($proj_id);
		else
			$vars['project'] = new object();

		$vars['languages'] = $this->make_query([], 'language')->get_result();

		return $vars;
	}

	public function form_language_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->get_record($proj_id);
			$project->languages = $this->get_languages($proj_id)->key_map(function($language) {
				return $language->id;
			});

			$vars['project'] = $project;
			$vars['languages'] = $this->make_query([], 'language')->get_result();
		}

		return $vars;
	}

	public function form_user_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->get_record($proj_id);
			$project->users = $project->get_users($proj_id)->key_map(function($user) {
				return $user->id;
			});

			$vars['project'] = $project;
			$vars['users'] = $this->make_query([], 'user')->get_result();
			$vars['roles'] = $this->make_query([], 'role')->get_result();
		}

		return $vars;
	}

	public function card_languages_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->get_record($proj_id);
			$project->languages = $this->get_languages($proj_id);

			$vars['project'] = $project;
		}

		return $vars;
	}

	public function card_users_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->get_record($proj_id);
			$project->users = $this->get_users($proj_id);

			$vars['project'] = $project;
		}

		return $vars;
	}

	public function card_documents_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->get_record($proj_id);
			$project->documents = $this->get_documents($proj_id);

			$vars['project'] = $project;
		}

		return $vars;
	}

	public function card_lists_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->get_record($proj_id);
			$project->lists = $this->get_lists($proj_id);

			$vars['project'] = $project;
		}

		return $vars;
	}

	public function api_view() {
		if ($id = get_resource_id()) {
			$project = $this->get_record($id);

			echo json_encode($project);
		} else {
			$limit  = get_per_page();
			$offset = get_offset(get_page(), $limit);

			$projects = $this->make_query(compact('limit', 'offset'))->get_result();

			echo json_encode($projects);
		}
	}

	public function fill_project(&$project) {
		$project->default_language = $this->get_record($project->default_language_id, 'language');

		$project->languages = $this->get_languages($project->id);
		$project->documents = $this->get_documents($project->id);
		$project->lists     = $this->get_lists($project->id);
		$project->users     = $this->get_users($project->id);

		return $project;
	}

	protected function get_languages($proj_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['bridge'] = 'pl_language';
		$args['args'] = ['pl_project' => $proj_id];

		return $this->make_query($args, 'language')->get_result();
	}

	protected function get_documents($proj_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['args'] = [
			'project_document' => $proj_id,
			'master_id'        => 0,
			'revision'         => 0
		];

		$documents = $this->make_query($args, 'document')->get_result();
		$documents->walk(function(&$document) {
			$args = [
				'args' => [
					'master_id' => $document->master_id ?: $document->id,
					'revision'  => 0
				]
			];

			$translations = $this->make_query($args, 'document')->get_result();
			$translations = $translations->key_map(function($translation) {
				return $translation->language_id;
			});

			$document->translations = $translations;
		});

		return $documents;
	}

	protected function get_lists($proj_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['args'] = [
			'project_list' => $proj_id,
			'master_id'    => 0,
			'revision'     => 0
		];

		return $this->make_query($args, 'list')->get_result();
	}

	protected function get_users($proj_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['bridge'] = 'up_user';
		$args['args'] = ['up_project' => $proj_id];

		$users = $this->make_query($args, 'user')->get_result();
		$users->walk(function(&$user) use ($proj_id) {
			$user->role = $this->make_query([
				'bridge' => 'up_role',
				'args'   => [
					'up_user'    => $user->id,
					'up_project' => $proj_id
				]
			], 'role')->get_result()->first;
		});

		return $users;
	}
}
