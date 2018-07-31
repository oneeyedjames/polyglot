<?php

class term_renderer extends renderer {
	public function __construct() {
		parent::__construct('term');
	}

	protected function map_field_name($field) {
		switch ($field) {
			case 'master_id':
			case 'language_id':
			case 'list_id':
			case 'user_id':
				return false;
			case 'descrip':
				return 'description';
			default:
				return parent::map_field_name($field);
		}
	}

	protected function map_field_value($value, &$field) {
		$value = parent::map_field_value($value, $field);

		switch ($field) {
			case 'revision':
				return boolval($field);
			default:
				return $value;
		}
	}

	protected function get_links($record) {
		$links = parent::get_links($record);
		$links['language'] = ['resource' => 'language', 'id' => $record->language_id];
		$links['list']     = ['resource' => 'list',     'id' => $record->list_id];
		$links['user']     = ['resource' => 'user',     'id' => $record->user_id];

		if ($record->master_id)
			$links['master'] = ['resource' => 'term', 'id' => $record->master_id];

		return $links;
	}
}
