<?php

class list_controller extends controller {
	public function __construct($database, $cache = null) {
		parent::__construct('list', $database, $cache);
	}

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
		$proj_id = get_filter('project');
		$vars['project'] = $this->get_record($proj_id, 'project');

		$limit = get_per_page();
		$offset = get_offset(get_page(), $limit);

		$vars['lists'] = $this->get_lists($proj_id, $limit, $offset);

		return $vars;
	}

	public function item_view($vars) {
		$list = $this->get_list(get_resource_id());
		$list->language = $this->get_record($list->project->default_language_id, 'language');

		$limit = get_per_page();
		$offset = get_offset(get_page(), $limit);

		$terms = $this->get_terms($list->id, $list->project->default_language_id, $limit, $offset);

		if ($lang_id = get_filter('translation')) {
			$vars['language'] = $this->get_record($lang_id, 'language');

			$trans = $this->make_query([
				'args' => [
					'list_term'        => $list->id,
					'language_term'    => $lang_id,
					'term`.`master_id' => $terms->keys,
					'term`.`revision'  => 0
				]
			], 'term')->get_result();

			$trans = $trans->key_map(function($term) {
				return $term->master_id;
			});

			$terms->walk(function(&$term) use ($trans) {
				if ($tran = @$trans[$term->id])
					$term->translation = $tran;
			});
		}

		$list->terms = $terms;

		$vars['list'] = $list;

		return $vars;
	}

	public function form_meta_view($vars) {
		if ($list_id = get_resource_id()) {
			$vars['list'] = $this->get_list($list_id);
		} elseif ($proj_id = get_filter('project')) {
			$list = new object();
			$list->project = $this->get_project($proj_id);

			$vars['list'] = $list;
		}

		return $vars;
	}

	public function get_lists($proj_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['args'] = ['project_list' => $proj_id];

		$lists = $this->make_query($args, 'list')->get_result();
		$lists->walk([$this, 'fill_list']);

		return $lists;
	}

	public function get_list($list_id) {
		if ($list = $this->get_record($list_id))
			return $this->fill_list($list);

		return false;
	}

	public function fill_list(&$list) {
		$list->project = $this->get_project($list->project_id);
		$list->user    = $this->get_record($list->user_id, 'user');

		return $list;
	}

	protected function get_project($proj_id) {
		$project = $this->get_record($proj_id, 'project');
		$project->languages = $this->make_query([
			'bridge' => 'pl_language',
			'args'   => [
				'pl_project' => $proj_id
			]
		], 'language')->get_result();

		return $project;
	}

	protected function get_terms($list_id, $lang_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['args'] = [
			'list_term'       => $list_id,
			'language_term'   => $lang_id,
			'term`.`revision' => 0
		];

		$terms = $this->make_query($args, 'term')->get_result();
		$terms = $terms->key_map(function($term) { return $term->id; });
		$terms->walk(function(&$term) {
			$term->user = $this->get_record($term->user_id, 'user');
		});

		return $terms;
	}
}
