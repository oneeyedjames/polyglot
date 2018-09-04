<?php

class document_model extends model {
	public function __construct($database, $cache) {
		parent::__construct('document', $database, $cache);

		$this->register_parent_relation('master',   'master',   'master_id');
		$this->register_parent_relation('project',  'project',  'project_id');
		$this->register_parent_relation('language', 'language', 'language_id');
		$this->register_parent_relation('user',     'user',     'user_id');

		$this->register_child_relation('revisions',    'document', 'get_revisions_by_master_id');
		$this->register_child_relation('translations', 'document', 'get_translations_by_master_id');
	}

	public function get_translation($master_id, $lang_id, $rels) {
		$args = [
			'args' => [
				'language_document' => $lang_id,
				'master_id'         => $master_id,
				'revision'          => 0
			]
		];

		return $this->make_query($args)->get_result()->first;
	}

	public function get_by_project_id($proj_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['args'] = [
			'project_document' => $proj_id,
			'master_id'        => 0,
			'revision'         => 0
		];

		$documents = $this->make_query($args)->get_result();

		if ($documents->found)
			$this->get_translations($documents);

		return $documents;
	}

	public function get_revisions_by_master_id($master_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['args'] = [
			'master_id' => $master_id,
			'revision'  => 1
		];

		$revisions = $this->make_query($args)->get_result();

		$this->get_users($revisions);

		return $revisions;
	}

	public function get_translations_by_master_id($master_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['args'] = [
			'master_id' => $master_id,
			'revision'  => 0
		];

		return $this->make_query($args)->get_result();
	}

	protected function get_users(&$documents) {
		$user_ids = $documents->map(function($document) {
			return $document->user_id;
		})->toArray();

		if (!empty($user_ids)) {
			$args = ['args' => ['id' => $user_ids]];

			$users = model::load('user')->make_query($args)->get_result();

			$documents->walk(function(&$document) use ($users) {
				foreach ($users as $user) {
					if ($document->user_id == $user->id) {
						$document->user = $user;
						break;
					}
				}
			});
		}
	}

	protected function get_translations(&$documents) {
		$master_ids = $documents->map(function($document) {
			return $document->master_id ?: $document->id;
		});

		$translations = $this->make_query([
			'args' => [
				'master_id' => $master_ids,
				'revision'  => 0
			]
		])->get_result();

		$documents->walk(function(&$document) use ($translations) {
			$master_id = $document->master_id ?: $document->id;
			$doc_trans = $document->translations ?: [];

			foreach ($translations as $translation) {
				if ($master_id == $translation->master_id)
					$doc_trans[] = $translation;
			}

			$document->translations = $doc_trans;
		});
	}

	protected function filter_args($args) {
		$proj_id = get_filter('project');
		$project = model::load('project')->get_record($proj_id);

		$lang_id = get_filter('language') ?: $project->default_language_id;

		$args['args']['project_document']  = $proj_id;
		$args['args']['language_document'] = $lang_id;
		$args['args']['revision']          = 0;

		return $args;
	}
}
