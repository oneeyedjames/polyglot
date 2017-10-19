<?php

class language_controller extends controller {
	public function __construct($database, $cache = null) {
		parent::__construct('language', $database, $cache);
	}

	public function save_action() {
		extract($_POST['language']);

		$params = array($code, $name);

		if ($id = get_resource_id()) {
			$params[] = $id;
			$sql = "UPDATE language SET code = ?, name = ? WHERE id = ?";
		} else {
			$sql = "INSERT INTO language (code, name) VALUES (?, ?)";
		}

		$this->execute($sql, $params);

		return array('resource' => 'language');
	}

	public function delete_action() {
		if ($id = get_resource_id()) {
			$sql = "DELETE FROM language WHERE id = ?";
			$this->execute($sql, array($id));
		}

		return array('resource' => 'language');
	}

	public function index_view($vars) {
		$limit = get_per_page();
		$offset = get_offset(get_page(), $limit);

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
}
