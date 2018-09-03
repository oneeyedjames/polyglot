<?php

class term_resource extends resource {
	public function __construct($database, $cache = false) {
		parent::__construct('term', $database, $cache);
	}

	public function get_by_list_id($list_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['args'] = [
			'list_term'        => $list_id,
			'term`.`master_id' => 0,
			'term`.`revision'  => 0
		];

		$terms = $this->make_query($args)->get_result();

		$this->get_users($terms);

		return $terms;
	}

	public function get_by_list_lang_id($list_id, $lang_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['args'] = [
			'list_term'       => $list_id,
			'language_term'   => $lang_id,
			'term`.`revision' => 0
		];

		$terms = $this->make_query($args)->get_result();

		return $terms;
	}

	protected function get_users(&$terms) {
		$user_ids = $terms->map(function($term) {
			return $term->user_id;
		});

		$args = ['args' => ['id' => $user_ids->toArray()]];

		$users = resource::load('user')->make_query($args)->get_result();

		$terms->walk(function(&$term) use ($users) {
			foreach ($users as $user) {
				if ($term->user_id == $user->id) {
					$term->user = $user;
					break;
				}
			}
		});
	}
}
