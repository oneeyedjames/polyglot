<?php

class project_controller extends controller {
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
		$projects = $this->_resource->get_result();
		$projects->walk(function(&$project) {
			$this->fill_project($project);
		});

		$vars['projects'] = $projects;

		return $vars;
	}

	public function item_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->_resource->get_record($proj_id);

			$this->fill_project($project);

			$vars['project'] = $project;
		}

		return $vars;
	}

	public function form_meta_view($vars) {
		if ($proj_id = get_resource_id())
			$vars['project'] = $this->_resource->get_record($proj_id);
		else
			$vars['project'] = new object();

		$vars['languages'] = resource::load('language')->get_all();

		return $vars;
	}

	public function form_language_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->_resource->get_record($proj_id);
			$project->languages = $this->get_languages($proj_id)->key_map(function($language) {
				return $language->id;
			});

			$vars['project'] = $project;
			$vars['languages'] = resource::load('language')->get_all();
		}

		return $vars;
	}

	public function form_user_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->_resource->get_record($proj_id);
			$project->users = $this->get_users($proj_id)->key_map(function($user) {
				return $user->id;
			});

			$vars['project'] = $project;
			$vars['users'] = resource::load('user')->get_all();
			$vars['roles'] = resource::load('role')->get_all();
		}

		return $vars;
	}

	public function card_languages_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->_resource->get_record($proj_id);
			$project->languages = $this->get_languages($proj_id);

			$vars['project'] = $project;
		}

		return $vars;
	}

	public function card_users_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->_resource->get_record($proj_id);
			$project->users = $this->get_users($proj_id);

			$vars['project'] = $project;
		}

		return $vars;
	}

	public function card_documents_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->_resource->get_record($proj_id);
			$project->documents = $this->get_documents($proj_id);

			$vars['project'] = $project;
		}

		return $vars;
	}

	public function card_lists_view($vars) {
		if ($proj_id = get_resource_id()) {
			$project = $this->_resource->get_record($proj_id);
			$project->lists = $this->get_lists($proj_id);

			$vars['project'] = $project;
		}

		return $vars;
	}

	public function fill_project(&$project) {
		$project->default_language = resource::load('language')->get_record($project->default_language_id);

		$project->languages = $this->get_languages($project->id);
		$project->documents = $this->get_documents($project->id);
		$project->lists     = $this->get_lists($project->id);
		$project->users     = $this->get_users($project->id);

		return $project;
	}

	protected function get_languages($proj_id) {
		return resource::load('language')->get_by_project_id($proj_id);
	}

	protected function get_documents($proj_id) {
		return resource::load('document')->get_by_project_id($proj_id);

		$documents->walk(function(&$document) {
			$translations = $this->make_query([
				'args' => [
					'master_id' => $document->master_id ?: $document->id,
					'revision'  => 0
				]
			], 'document')->get_result()->key_map(function($translation) {
				return $translation->language_id;
			});

			$document->translations = $translations;
		});

		return $documents;
	}

	protected function get_lists($proj_id) {
		return resource::load('list')->get_by_project_id($proj_id);
	}

	protected function get_users($proj_id) {
		return resource::load('user')->get_by_project_id($proj_id);
	}
}
