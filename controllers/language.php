<?php

class language_controller extends controller {
	public function __construct($database, $cache = null) {
		parent::__construct('language', $database, $cache);
	}

	public function save_action($get, $post) {
		$language = new object();
        $language->id = get_resource_id();

        if (isset($post['language']['code']))
            $language->code = $post['language']['code'];

        if (isset($post['language']['name']))
            $language->name = $post['language']['name'];

		if (!empty($language->code) && !empty($language->name))
			$this->_resource->put_record($language);

		return ['resource' => 'language'];
	}

	public function index_view($vars) {
		$languages = $this->_resource->get_result();
		$languages->walk(function(&$language) {
			$language->projects = resource::load('project')->get_by_language_id($language->id);
			$language->users    = resource::load('user')->get_by_language_id($language->id);
		});

		$vars['languages'] = $languages;

		return $vars;
	}

	public function form_meta_view($vars) {
		if ($lang_id = get_resource_id())
			$vars['language'] = $this->_resource->get_record($lang_id);
		else
			$vars['language'] = new object();

		return $vars;
	}

	public function card_projects_view($vars) {
		if ($lang_id = get_resource_id())
			$vars['language'] = $this->_resource->get_record($lang_id, ['project']);

		return $vars;
	}

	public function card_users_view($vars) {
		if ($lang_id = get_resource_id())
			$vars['language'] = $this->_resource->get_record($lang_id, ['user']);

		return $vars;
	}
}
