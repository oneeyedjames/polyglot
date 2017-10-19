<?php

class term_controller extends controller {
	public function __construct($database, $cache = null) {
		parent::__construct('term', $database, $cache);
	}

	public function save_action($get, $post) {
		$term = new object();
		$term->user_id = SESSION_USER_ID;
		$term->updated = date('Y-m-d H:i:s');

		if (isset($post['term']['master']))
			$term->master_id = intval($post['term']['master']);

		if (isset($post['term']['list']))
			$term->list_id = intval($post['term']['list']);
		elseif ($list_id = get_filter('list'))
			$term->list_id = intval($list_id);

		$list = $this->get_record($term->list_id, 'list') ?: new object();

		if (isset($post['term']['language']))
			$term->language_id = intval($post['term']['language']);
		elseif ($lang_id = get_filter('language'))
			$term->language_id = intval($lang_id);
		elseif ($project = $this->get_record($list->project_id, 'project'))
			$term->language_id = intval($project->default_language_id);

		if (isset($post['term']['content']))
			$term->content = $post['term']['content'];

		if (isset($post['term']['description']))
			$term->descrip = $post['term']['description'];

		if ($term->id = get_resource_id()) {
			$revision = $this->get_record($term->id);
			$revision->master_id = $term->id;
			$revision->created = $revision->updated;
			$revision->revision = 1;
			unset($revision->id, $revision->updated);

			$term->language_id = $revision->language_id;
			$term->list_id     = $revision->list_id;

			$this->put_record($term);
			$this->put_record($revision);
		} else {
			$term->created = $term->updated;
			$term->id = $this->put_record($term);
		}

		$params = array('resource' => 'list', 'id' => $term->list_id);

		if ($term->language_id)
			$params['filter']['translation'] = $term->language_id;

		return $params;
	}

	public function form_meta_view($vars) {
		if ($term_id = get_resource_id()) {
			$term = $this->get_term($term_id);

			if ($lang_id = get_filter('translation')) {
				if ($lang_id != $term->language_id) {
					$master = $term;

					$term = $this->get_term($term_id, $lang_id) ?: $term = new object();
					$term->master = $master;
					$term->list = $this->get_record($master->list_id, 'list');
					$term->language = $this->get_record($lang_id, 'language');
				}
			}
		} elseif ($list_id = get_filter('list')) {
			$term = new object();
			$term->list = $this->get_record($list_id, 'list');
		}

		$vars['term'] = $term;

		return $vars;
	}

	public function api_view() {
		if (isset($_REQUEST['filter']['list'])) {
			$terms = $this->make_query(array(
				'list_id'     => get_filter('list'),
				'language_id' => 1,
				'revision'    => 0
			))->get_result();

			echo json_encode($terms);
		}
	}

	public function get_term($term_id, $lang_id = false) {
		if ($lang_id) {
			$query = $this->make_query(array(
				'args' => array(
					'language_term' => $lang_id,
					'master_id'     => $term_id,
					'revision'      => 0
				)
			));

			if ($term = $query->get_result()->first)
				return $term;
		} else {
			if ($term = $this->get_record($term_id))
				return $term;
		}

		return false;
	}
}
