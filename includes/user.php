<?php

class user extends user_base {
	protected $_id;
	protected $_admin;
	protected $_role;

	public $display_name;

	public function __construct($record, $role = false) {
		parent::__construct($record->email, $record->password);

		$this->_id = (int)$record->id;
		$this->_admin = (bool)$record->admin;

		$this->display_name = $record->name;

		if ($role)
			$this->_role = $role;
	}

	public function has_permission($action, $resource = false) {
		if ($this->admin)
			return true;

		if (!$this->role)
			return false;

		foreach ($this->role->permissions as $permission) {
			if ($permission->action == $action && $permission->resource == $resource)
				return true;
		}

		return false;
	}
}
