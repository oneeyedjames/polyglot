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

        $this->put_record($language);

		return array('resource' => 'language');
	}

	public function delete_action($get, $post) {
		if ($id = get_resource_id())
			$this->remove_record($id);

		return array('resource' => 'language');
	}

	public function index_view($vars) {
		$vars['limit']  = $limit  = get_per_page();
		$vars['offset'] = $offset = get_offset(get_page(), $limit);

		$args = compact('limit', 'offset');

		$vars['languages'] = $this->make_query($args)->get_result();
		$vars['languages']->walk(function(&$language) {
			$language->projects = $this->make_query(array(
				'bridge' => 'pl_project',
				'limit'  => 3,
				'args'   => array(
					'pl_language' => $language->id
				)
			), 'project')->get_result();

			$language->users = $this->make_query(array(
				'bridge' => 'ul_user',
				'limit'  => 3,
				'args'   => array(
					'ul_language' => $language->id
				)
			), 'user')->get_result();
		});

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
			$language->projects = $this->make_query(array(
				'bridge' => 'pl_project',
				'limit'  => 3,
				'args'   => array(
					'pl_language' => $language->id
				)
			), 'project')->get_result();

			$vars['language'] = $language;
		}

		return $vars;
	}

	public function card_users_view($vars) {
		if ($lang_id = get_resource_id()) {
			$language = $this->get_record($lang_id);
			$language->users = $this->make_query(array(
				'bridge' => 'ul_user',
				'limit'  => 3,
				'args'   => array(
					'ul_language' => $language->id
				)
			), 'user')->get_result();

			$vars['language'] = $language;
		}

		return $vars;
	}
}
