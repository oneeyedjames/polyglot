<?php

class document_resource extends resource {
	public function __construct($database, $cache) {
		parent::__construct('document', $database, $cache);
	}

	public function get_by_project_id($proj_id) {
		$documents = $this->make_query([
			'args' => [
				'project_document' => $proj_id,
				'master_id'        => 0,
				'revision'         => 0
			]
		])->get_result();

		if ($documents->found) {
			$this->get_translations($documents);
		}

		return $documents;
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
}
