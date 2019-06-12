<?php

class language_controller extends controller {
	public function save_action($get, $post) {
		$language = $this->create_record(get_resource_id());

        if (isset($post['language']['code']))
            $language->code = $post['language']['code'];

        if (isset($post['language']['name']))
            $language->name = $post['language']['name'];

		if (!empty($language->code) && !empty($language->name))
			$this->put_record($language);

		return ['resource' => 'language'];
	}

	public function index_view($vars) {
		$vars['languages'] = $this->get_result([], ['projects', 'users']);

		return $vars;
	}

	public function form_meta_view($vars) {
		if ($lang_id = get_resource_id())
			$vars['language'] = $this->get_record($lang_id);
		else
			$vars['language'] = $this->create_record();

		return $vars;
	}

	public function card_projects_view($vars) {
		if ($lang_id = get_resource_id())
			$vars['language'] = $this->get_record($lang_id, ['projects']);

		return $vars;
	}

	public function card_users_view($vars) {
		if ($lang_id = get_resource_id())
			$vars['language'] = $this->get_record($lang_id, ['users']);

		return $vars;
	}
}
