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
			$this->put_record($language);

		return ['resource' => 'language'];
	}

	public function index_view($vars) {
		$languages = $this->get_result();
		$languages->walk(function(&$language) {
			$language->projects = $this->get_projects($language->id);
			$language->users    = $this->get_users($language->id);
		});

		$vars['languages'] = $languages;

		return $vars;
	}

	public function form_meta_view($vars) {
		if ($lang_id = get_resource_id())
			$vars['language'] = $this->get_record($lang_id);
		else
			$vars['language'] = new object();

		return $vars;
	}

	public function card_projects_view($vars) {
		if ($lang_id = get_resource_id()) {
			$language = $this->get_record($lang_id);
			$language->projects = $this->get_projects($lang_id);

			$vars['language'] = $language;
		}

		return $vars;
	}

	public function card_users_view($vars) {
		if ($lang_id = get_resource_id()) {
			$language = $this->get_record($lang_id);
			$language->users = $this->get_users($lang_id);

			$vars['language'] = $language;
		}

		return $vars;
	}

	protected function get_default_sorting() {
		return ['code' => 'asc'];
	}

	protected function get_projects($lang_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['bridge'] = 'pl_project';
		$args['args'] = ['pl_language' => $lang_id];

		return $this->make_query($args, 'project')->get_result();
	}

	protected function get_users($lang_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['bridge'] = 'ul_user';
		$args['args'] = ['ul_language' => $lang_id];

		return $this->make_query($args, 'user')->get_result();
	}
}
