<?php

class user_renderer extends renderer {
	public function __construct() {
		parent::__construct('user');
	}

	public function map_field_name($field) {
		switch ($field) {
			case 'admin':
			case 'password':
			case 'reset_token':
			case 'reset_expire':
				return false;
			default:
				return $field;
		}
	}
}
