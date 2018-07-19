<?php

class project_renderer extends renderer {
	public function __construct() {
		parent::__construct('project');
	}

	public function map_field_name($field) {
		switch ($field) {
			case 'default_language_id':
				return false;
			case 'descrip':
				return 'description';
			default:
				return $field;
		}
	}

	public function get_links($record) {
		$links = parent::get_links($record);
		$links['language'] = [
			'resource' => 'language',
			'id' => $record->default_language_id
		];

		return $links;
	}
}
