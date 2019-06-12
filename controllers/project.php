<?php

class project_controller extends controller {
	public function save_action($get, $post) {
		$project = $this->create_record(get_resource_id());

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

		if (isset($post['language']))
			$this->add_language($project_id, $post['language']);

		return ['resource' => 'project', 'id' => $project_id];
	}

	public function remove_language_action($get, $post) {
		$project_id = get_resource_id();

		if (isset($post['language']))
			$this->remove_language($project_id, $post['language']);

		return ['resource' => 'project', 'id' => $project_id];
	}

	public function add_user_action($get, $post) {
		$project_id = get_resource_id();

		if (isset($post['user'], $post['role']))
			$this->add_user($project_id, $post['user'], $post['role']);

		return ['resource' => 'project', 'id' => $project_id];
	}

	public function remove_user_action($get, $post) {
		$project_id = get_resource_id();

		if (isset($post['user']))
			$this->remote_user($project_id, $post['user']);

		return ['resource' => 'project', 'id' => $project_id];
	}

	public function index_view($vars) {
		$rels = ['languages', 'users', 'documents', 'lists'];

		$vars['projects'] = $this->get_result([], $rels);

		return $vars;
	}

	public function item_view($vars) {
		$rels = ['default_language', 'languages', 'users', 'documents', 'lists'];

		if ($proj_id = get_resource_id())
			$vars['project'] = $this->get_record($proj_id, $rels);

		return $vars;
	}

	public function form_meta_view($vars) {
		if ($proj_id = get_resource_id())
			$vars['project'] = $this->get_record($proj_id);
		else
			$vars['project'] = $this->create_record();

		$vars['languages'] = $this->get_languages();

		return $vars;
	}

	public function form_language_view($vars) {
		if ($proj_id = get_resource_id()) {
			$vars['project'] = $this->get_record($proj_id, ['languages']);
			$vars['languages'] = $this->get_languages();
		}

		return $vars;
	}

	public function form_user_view($vars) {
		if ($proj_id = get_resource_id()) {
			$vars['project'] = $this->get_record($proj_id, ['users']);
			$vars['users'] = $this->get_users();
			$vars['roles'] = $this->get_roles();
		}

		return $vars;
	}

	public function card_languages_view($vars) {
		if ($proj_id = get_resource_id())
			$vars['project'] = $this->get_record($proj_id, ['languages']);

		return $vars;
	}

	public function card_users_view($vars) {
		if ($proj_id = get_resource_id())
			$vars['project'] = $this->get_record($proj_id, ['users']);

		return $vars;
	}

	public function card_documents_view($vars) {
		if ($proj_id = get_resource_id())
			$vars['project'] = $this->get_record($proj_id, ['documents']);

		return $vars;
	}

	public function card_lists_view($vars) {
		if ($proj_id = get_resource_id())
			$vars['project'] = $this->get_record($proj_id, ['lists']);

		return $vars;
	}

	protected function get_languages() {
		return model::load('language')->get_all();
	}

	protected function get_users() {
		return model::load('user')->get_all();
	}

	protected function get_roles() {
		return model::load('role')->get_all();
	}
}
