<?php

class list_controller extends controller {
	public function save_action($get, $post) {
		$list = new object();
		$list->user_id = SESSION_USER_ID;
		$list->updated = date('Y-m-d H:i:s');

		if (isset($post['list']['project']))
			$list->project_id = intval($post['list']['project']);
		elseif ($proj_id = get_filter('project'))
			$list->project_id = intval($proj_id);

		if (isset($post['list']['title']))
			$list->title = $post['list']['title'];

		if (isset($post['list']['description']))
			$list->descrip = $post['list']['description'];

		if ($list->id = get_resource_id()) {
			$rev = $this->get_record($list->id);
			$rev->master_id = $list->id;
			$rev->revision = 1;
			$rev->created = $rev->updated;
			unset($rev->id, $rev->updated);

			$this->put_record($rev);
			$this->put_record($list);
		} else {
			$list->created = $list->updated;
			$list->id = $this->put_record($list);
		}

		return ['resource' => 'list', 'id' => $list->id];
	}

	public function index_view($vars) {
		$vars['lists'] = $this->get_result([], ['user']);

		if ($proj_id = get_filter('project'))
			$vars['project'] = $this->get_project($proj_id);

		return $vars;
	}

	public function item_view($vars) {
		if ($list_id = get_resource_id()) {
			$vars['list'] = $list = $this->get_record($list_id, ['user']);
			$vars['list']['project'] = $project = $this->get_project($list->project_id);
			$vars['list']['language'] = $this->get_language($project->default_language_id);

			$list->terms = $this->get_terms($list_id);

			if ($lang_id = get_filter('translation')) {
				if ($lang_id != $project->default_language_id) {
					$vars['language'] = $this->get_language($lang_id);

					$trans = $this->get_terms($list_id, $lang_id);
					$trans = $trans->key_map(function($term) {
						return $term->master_id;
					});

					$list->terms->walk(function(&$term) use ($trans) {
						if ($tran = @$trans[$term->id])
							$term->translation = $tran;
					});
				}
			}
		}

		return $vars;
	}

	public function form_meta_view($vars) {
		if ($list_id = get_resource_id()) {
			$vars['list'] = $this->get_record($list_id);
		} elseif ($proj_id = get_filter('project')) {
			$vars['list'] = new object([
				'project' => $this->get_project($proj_id)
			]);
		}

		return $vars;
	}

	protected function filter_args($args) {
		if ($proj_id = get_filter('project'))
			$args['args']['project_list'] = $proj_id;

		return $args;
	}

	protected function get_project($proj_id) {
		return model::load('project')->get_record($proj_id, ['languages']);
	}

	protected function get_language($lang_id) {
		return model::load('language')->get_record($lang_id);
	}

	protected function get_terms($list_id, $lang_id = 0) {
		$limit = get_per_page();
		$offset = get_offset(get_page(), $limit);

		if ($lang_id)
			return model::load('term')->get_by_list_lang_id($list_id, $lang_id, $limit, $offset);
		else
			return model::load('term')->get_by_list_id($list_id, $limit, $offset);
	}
}
