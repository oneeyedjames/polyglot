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

		if ($project->id && isset($post['languages'])) {
			$sql = 'DELETE FROM `project_language_map` WHERE `project_id` = ?';
			$this->execute($sql, $project->id);

			foreach ($post['languages'] as $lang_id) {
				$record = new object(array(
					'project_id'  => $project->id,
					'language_id' => intval($lang_id)
				));

				$this->put_record($record, 'project_language_map');
			}
		}

		return array('resource' => 'project', 'id' => $project->id);
	}

	// TODO Method Stub
	public function delete_action($get, $post) {
		return array('resource' => 'project');
	}

	public function add_language_action($get, $post) {
		$project_id = get_resource_id();

		if (isset($post['language'])) {
			$this->put_record(new object(array(
				'project_id' => $project_id,
				'language_id' => intval($post['language'])
			)), 'project_language_map');
		}

		return array('resource' => 'project', 'id' => $project_id);
	}

	public function remove_language_action($get, $post) {
		$project_id = get_resource_id();

		if (isset($post['language'])) {
			$sql = 'DELETE FROM `project_language_map` WHERE `project_id` = ? AND `language_id` = ?';
			$this->execute($sql, $project_id, intval($post['language']));
		}

		return array('resource' => 'project', 'id' => $project_id);
	}

	public function add_user_action($get, $post) {
		$project_id = get_resource_id();

		if (isset($post['user'], $post['role'])) {
			$record = new object(array(
				'project_id' => $project_id,
				'user_id'    => intval($post['user']),
				'role_id'    => intval($post['role'])
			));

			$this->put_record($record, 'user_project_map');
		}

		return array('resource' => 'project', 'id' => $project_id);
	}

	public function remove_user_action($get, $post) {
		$project_id = get_resource_id();

		if (isset($post['user'])) {
			$sql = 'DELETE FROM `user_project_map` WHERE `project_id` = ? AND `user_id` = ?';
			$this->execute($sql, $project_id, intval($post['user']));
		}

		return array('resource' => 'project', 'id' => $project_id);
	}

	public function index_view($vars) {
		$limit = get_per_page();
		$offset = get_offset(get_page(), $limit);

		$vars['projects'] = $this->get_projects($limit, $offset);

		return $vars;
	}

	public function item_view($vars) {
		$vars['project'] = $this->get_project(get_resource_id());

		return $vars;
	}

	public function form_meta_view($vars) {
		if ($proj_id = get_resource_id()) {
			$vars['project'] = $this->get_project($proj_id);
		} else {
			$vars['project'] = new object();
		}

		$vars['languages'] = $this->make_query([], 'language')->get_result();

		return $vars;
	}

	public function form_language_view($vars) {
		$project = $this->get_project(get_resource_id());
		$project->languages = $project->languages->key_map(function($language) {
			return $language->id;
		});

		$vars['project'] = $project;
		$vars['languages'] = $this->make_query([], 'language')->get_result();

		return $vars;
	}

	public function form_user_view($vars) {
		$project = $this->get_project(get_resource_id());
		$project->users = $project->users->key_map(function($user) {
			return $user->id;
		});

		$vars['project'] = $project;
		$vars['users'] = $this->make_query([], 'user')->get_result();
		$vars['roles'] = $this->query('SELECT * FROM role');

		return $vars;
	}

	public function card_languages_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->get_record($proj_id);
			$project->languages = $this->make_query(array(
				'bridge' => 'pl_language',
				'limit'  => 3,
				'args'   => array(
					'pl_project' => $proj_id
				)
			), 'language')->get_result();

			$vars['project'] = $project;
		}

		return $vars;
	}

	public function card_users_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->get_record($proj_id);
			$project->users = $this->make_query(array(
				'bridge' => 'up_user',
				'limit'  => 3,
				'args'   => array(
					'up_project' => $proj_id
				)
			), 'user')->get_result();

			$vars['project'] = $project;
		}

		return $vars;
	}

	public function card_documents_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->get_record($proj_id);
			$project->documents = $this->make_query(array(
				'args' => array(
					'project_document' => $proj_id,
					'master_id'        => 0,
					'revision'         => 0
				)
			), 'document')->get_result();

			$vars['project'] = $project;
		}

		return $vars;
	}

	public function card_lists_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->get_record($proj_id);
			$project->lists = $this->make_query(array(
				'args' => array(
					'project_list' => $proj_id,
					'master_id'    => 0,
					'revision'     => 0
				)
			), 'list')->get_result();

			$vars['project'] = $project;
		}

		return $vars;
	}

	public function api_view() {
		if ($id = get_resource_id()) {
			$project = $this->get_record($id);

			echo json_encode($project);
		} else {
			$limit = get_per_page();
			$offset = get_offset(get_page(), $limit);

			$projects = $this->make_query(compact('limit', 'offset'))->get_result();

			echo json_encode($projects);
		}
	}

	public function get_projects($limit = DEFAULT_PER_PAGE, $offset = 0) {
		$query = $this->make_query(compact('limit', 'offset'));

		$projects = $query->get_result();
		$projects->walk(array($this, 'fill_project'));

		return $projects;
	}

	public function get_project($proj_id) {
		if ($project = $this->get_record($proj_id))
			return $this->fill_project($project);

		return false;
	}

	public function fill_project(&$project) {
		$project->default_language = $this->get_record($project->default_language_id, 'language');

		$project->languages = $this->get_languages($project->id);
		$project->documents = $this->get_documents($project->id);
		$project->lists     = $this->get_lists($project->id);
		$project->users     = $this->get_users($project->id);

		$project->documents->walk(array($this, 'fill_document'));

		return $project;
	}

	public function fill_document(&$document) {
		$master_id = $document->master_id ?: $document->id;

		$query = $this->make_query(array(
			'args' => array(
				'master_id' => $master_id,
				'revision'  => 0
			)
		), 'document');

		$translations = $query->get_result();

		$document->translations = new object();

		foreach ($translations as $translation)
			$document->translations[$translation->language_id] = $translation;

		return $document;
	}

	protected function get_languages($proj_id) {
		return $this->make_query(array(
			'bridge' => 'pl_language',
			'args'   => array(
				'pl_project' => $proj_id
			)
		), 'language')->get_result();
	}

	protected function get_documents($proj_id) {
		$documents = $this->make_query(array(
			'args' => array(
				'project_document' => $proj_id,
				'master_id'        => 0,
				'revision'         => 0
			)
		), 'document')->get_result();

		$documents->walk(function(&$document) {
			$document->translations = $this->make_query(array(
				'master_id' => $document->id,
				'revision'  => 0
			))->get_result();
		});

		return $documents;
	}

	protected function get_lists($proj_id) {
		return $this->make_query(array(
			'project_list' => $proj_id,
			'master_id'    => 0
		), 'list')->get_result();
	}

	protected function get_users($proj_id) {
		$users = $this->make_query(array(
			'bridge' => 'up_user',
			'args'   => array(
				'up_project' => $proj_id
			)
		), 'user')->get_result();

		$users->walk(function(&$user) use ($proj_id) {
			$user->role = $this->make_query(array(
				'bridge' => 'up_role',
				'args'   => array(
					'up_user'    => $user->id,
					'up_project' => $proj_id
				)
			), 'role')->get_result()->first;
		});

		return $users;
	}
}
