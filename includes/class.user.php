<?php

class user extends user_base {
	protected $_id;
	protected $_admin;
	protected $_role;

	public $display_name;

	public function __construct($record, $role = false) {
		parent::__construct($record->email, $record->password);

		$this->_id = intval($record->id);
		$this->_admin = boolval($record->admin);

		$this->display_name = $record->alias;

		if ($role)
			$this->_role = $role;
	}

	public function has_permission($action, $resource = false) {
		if ($this->admin)
			return true;

		return boolval($this->get_permission($action, $resource));
	}

	public function get_permission($action, $resource = false) {
		if ($this->role) {
			foreach ($this->role->permissions as $permission) {
				if ($permission->action == $action &&
					$permission->resource == $resource)
					return $permission;
			}
		}

		return false;
	}
}
