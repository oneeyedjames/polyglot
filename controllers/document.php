<?php

class document_controller extends controller {
	public function save_action($get, $post) {
		$document = $this->create_record([
			'user_id' => SESSION_USER_ID,
			'updated' => date('Y-m-d H:i:s')
		]);

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
		$vars['documents'] = $this->get_result([], ['user']);

		if ($proj_id = get_filter('project'))
			$vars['project'] = $this->get_project($proj_id);

		return $vars;
	}

	public function item_view($vars) {
		$rels = ['language', 'user', 'revisions', 'translations'];

		$vars['document'] = $document = $this->get_record(get_resource_id(), $rels);
		$vars['document']['project'] = $this->get_project($document->project_id);

		if ($lang_id = get_filter('translation')) {
			if ($lang_id == $document->language_id) {
				$url = $this->build_url($document->id);
			} else {
				$master = $document;

				if ($document = $this->get_record($master->id, [], $lang_id)) {
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

		return $vars;
	}

	public function form_view($vars) {
		$rels = ['project', 'language', 'user'];

		$document = $this->get_record(get_resource_id(), $rels);

		if ($lang_id = get_filter('translation')) {
			if ($lang_id != $document->language_id) {
				$vars['master'] = $master = $document;

				if (!$document = $this->get_record($master->id, $rels, $lang_id)) {
					$document = $this->create_record([
						'master_id'   => $master->id,
						'master'      => $master,
						'project_id'  => $master->project_id,
						'project'     => $master->project,
						'language_id' => $lang_id,
						'language'    => $this->get_language($lang_id),
						'user_id'     => SESSION_USER_ID,
						'user'        => $this->get_user(SESSION_USER_ID)
					]);
				}
			}
		}

		$vars['document'] = $document;

		return $vars;
	}

	public function form_meta_view($vars) {
		if ($doc_id = get_resource_id()) {
			$vars['document'] = $this->get_record($doc_id, [], get_filter('translation'));
		} elseif ($proj_id = get_filter('project')) {
			$vars['document'] = $this->create_record([
				'project' => $this->get_project($proj_id)
			]);
		}

		return $vars;
	}

	protected function get_record($record_id, $rels = [], $lang_id = 0) {
		if ($lang_id)
			return $this->get_translation($record_id, $lang_id, $rels);

		return parent::get_record($record_id, $rels);
	}

	protected function get_project($proj_id) {
		return model::load('project')->get_record($proj_id, ['languages']);
	}

	protected function get_language($lang_id) {
		return model::load('language')->get_record($lang_id);
	}

	protected function get_user($user_id) {
		return model::load('user')->get_record($user_id);
	}
}
