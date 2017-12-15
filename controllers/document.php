<?php

class document_controller extends controller {
	public function __construct($database, $cache = null) {
		parent::__construct('document', $database, $cache);
	}

	public function save_action($get, $post) {
		$document = new object();
		$document->user_id = SESSION_USER_ID;
		$document->updated = date('Y-m-d H:i:s');

		if (isset($post['document']['master']))
			$document->master_id = intval($post['document']['master']);

		if (isset($post['document']['project']))
			$document->project_id = intval($post['document']['project']);
		elseif ($proj_id = get_filter('project'))
			$document->project_id = intval($proj_id);
		elseif ($master = $this->get_record($document->master_id))
			$document->project_id = intval($master->project_id);

		if (isset($post['document']['language']))
			$document->language_id = intval($post['document']['language']);
		elseif ($lang_id = get_filter('language'))
			$document->language_id = intval($lang_id);
		elseif ($project = $this->get_record($document->project_id, 'project'))
			$document->language_id = intval($project->default_language_id);

		if (isset($post['document']['title']))
			$document->title = $post['document']['title'];

		if (isset($post['document']['description']))
			$document->descrip = $post['document']['description'];

		if (isset($post['document']['content']))
			$document->content = $post['document']['content'];

		if ($document->id = get_resource_id()) {
			$revision = $this->get_record($document->id);
			$revision->master_id = $document->id;
			$revision->created = $revision->updated;
			$revision->revision = 1;
			unset($revision->id, $revision->updated);

			$this->put_record($document);
			$this->put_record($revision);
		} else {
			$document->created = $document->updated;
			$document->id = $this->put_record($document);
		}

		return ['resource' => 'document', 'id' => $document->id];
	}

	public function index_view($vars) {
		$proj_id = get_filter('project');
		$vars['project'] = $this->get_project($proj_id);

		$lang_id = get_filter('language') ?: $vars['project']->default_language_id;

		$limit = get_per_page();
		$offset = get_offset(get_page(), $limit);

		$vars['documents'] = $this->get_documents($proj_id, $lang_id, $limit, $offset);

		return $vars;
	}

	public function item_view($vars) {
		$document = $this->get_document(get_resource_id());

		if ($lang_id = get_filter('translation')) {
			if ($lang_id == $document->language_id) {
				$url = $this->build_url($document->id);
			} else {
				$master = $document;

				if ($document = $this->get_document($master->id, $lang_id)) {
					$url = $this->build_url($document->id);
				} else {
					$url = $this->build_url([
						'id'     => $master->id,
						'view'   => 'form',
						'filter' => [
							'translation' => $lang_id
						]
					]);
				}
			}

			$this->redirect($url);
		}

		$document->revisions = $this->get_revisions($document->revision ? $document->master_id : $document->id);
		$document->translations = new object();

		foreach ($this->get_translations($document->master_id ?: $document->id) as $translation)
			$document->translations[$translation->language->code] = $translation;

		$vars['document'] = $document;

		return $vars;
	}

	public function form_view($vars) {
		$document = $this->get_document(get_resource_id());
		$master_id = $document->master_id ?: $document->id;

		if ($lang_id = get_filter('translation')) {
			if ($lang_id != $document->language_id) {
				$master = $document;

				$vars['master'] = $master;

				$document = $this->get_document($master->id, $lang_id);

				if (!$document) {
					$document = new object();
					$document->master_id = $master_id;
					$document->master   = $master;
					$document->project  = $master->project;
					$document->language_id = $lang_id;
					$document->language = $this->get_record($lang_id, 'language');
					$document->user     = $this->get_record(SESSION_USER_ID, 'user');
				}
			}
		}

		$vars['document'] = $document;

		return $vars;
	}

	public function form_meta_view($vars) {
		if ($doc_id = get_resource_id()) {
			$vars['document'] = $this->get_document($doc_id, get_filter('translation'));
		} elseif ($proj_id = get_filter('project')) {
			$document = new object();
			$document->project  = $this->get_project($proj_id);

			$vars['document'] = $document;
		}

		return $vars;
	}

	public function get_documents($proj_id, $lang_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['args'] = [
			'project_document'  => $proj_id,
			'language_document' => $lang_id,
			'revision'          => 0
		];

		$documents = $this->make_query($args)->get_result();
		$documents->walk([$this, 'fill_document']);

		return $documents;
	}

	public function get_document($doc_id, $lang_id = false) {
		if ($lang_id) {
			$query = $this->make_query([
				'args' => [
					'language_document' => $lang_id,
					'master_id'         => $doc_id,
					'revision'          => 0
				]
			]);

			if ($document = $query->get_result()->first)
				return $this->fill_document($document);
		} else {
			if ($document = $this->get_record($doc_id))
				return $this->fill_document($document);
		}

		return false;
	}

	public function fill_document(&$document) {
		$document->master   = $this->get_document($document->master_id);
		$document->project  = $this->get_project($document->project_id);
		$document->language = $this->get_record($document->language_id, 'language');
		$document->user     = $this->get_record($document->user_id, 'user');

		return $document;
	}

	protected function get_project($proj_id) {
		$args = [
			'bridge' => 'pl_language',
			'args'   => [
				'pl_project' => $proj_id
			]
		];

		$project = $this->get_record($proj_id, 'project');
		$project->languages = $this->make_query($args, 'language')->get_result();

		return $project;
	}

	protected function get_revisions($doc_id) {
		$query = $this->make_query([
			'args' => [
				'master_id' => $doc_id,
				'revision'  => 1
			],
			'sort' => [
				'created' => 'desc'
			]
		));

		$result = $query->get_result();
		$result->walk([$this, 'fill_document']);

		return $result;
	}

	protected function get_translations($doc_id) {
		$query = $this->make_query([
			'args' => [
				'master_id' => $doc_id,
				'revision'  => 0
			]
		]);

		$result = $query->get_result();
		$result->walk([$this, 'fill_document']);

		return $result;
	}
}
