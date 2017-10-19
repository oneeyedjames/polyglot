<?php

class user extends user_base {
	protected $_id;
	protected $_admin;

	public $display_name;

	public function __construct($record) {
		parent::__construct($record->email, $record->password);

		$this->_id = (int)$record->id;
		$this->_admin = (bool)$record->admin;

		$this->display_name = $record->name;
	}
}
