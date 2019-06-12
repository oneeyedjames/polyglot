<?php

class term_controller extends controller {
	public function save_action($get, $post) {
		$term = $this->create_record([
			'user_id' => SESSION_USER_ID,
			'updated' => date('Y-m-d H:i:s')
		]);

		if (isset($post['term']['master']))
			$term->master_id = intval($post['term']['master']);

		if (isset($post['term']['list']))
			$term->list_id = intval($post['term']['list']);
		elseif ($list_id = get_filter('list'))
			$term->list_id = intval($list_id);

		$list = $this->get_record($term->list_id, 'list') ?: $this->create_record();

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
			$revision->revision = 1;
			$revision->created = $revision->updated;
			unset($revision->id, $revision->updated);

			$term->language_id = $revision->language_id;
			$term->list_id     = $revision->list_id;

			$this->put_record($term);
			$this->put_record($revision);
		} else {
			$term->created = $term->updated;
			$term->id = $this->put_record($term);
		}

		$params = ['resource' => 'list', 'id' => $term->list_id];

		if ($term->language_id != $project->default_language_id)
			$params['filter']['translation'] = $term->language_id;

		return $params;
	}

	public function form_meta_view($vars) {
		if ($term_id = get_resource_id()) {
			$term = $this->get_record($term_id);

			if ($lang_id = get_filter('translation')) {
				if ($lang_id != $term->language_id) {
					$list_id = $term->list_id;

					$master = $term;

					$term = $this->get_record($term_id, [], $lang_id) ?: $this->create_record();
					$term->master_id   = $master->id;
					$term->master      = $master;
					$term->list_id     = $list_id;
					$term->list        = $this->get_list($list_id);
					$term->language_id = $lang_id;
					$term->language    = $this->get_language($lang_id);
				}
			}
		} elseif ($list_id = get_filter('list')) {
			$term = $this->create_record([
				'list' => $this->get_list($list_id)
			]);
		}

		$vars['term'] = $term;

		return $vars;
	}

	protected function get_record($record_id, $rels = [], $lang_id = 0) {
		if ($lang_id)
			return $this->get_translation($record_id, $lang_id);

		return parent::get_record($record_id, $rels);
	}

	protected function get_list($list_id) {
		return model::load('list')->get_record($list_id);
	}

	protected function get_language($lang_id) {
		return model::load('language')->get_record($lang_id);
	}
}
