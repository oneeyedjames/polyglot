<?php

class resource extends resource_base {
	private static $_default_resource = false;
	private static $_default_database = false;
	private static $_default_cache    = false;

	private static $_resources = [];

	private $_relations = [];

	public static function init($database = false, $cache = false) {
		if (!self::$_default_database)
			self::$_default_database = $database;

		if (!self::$_default_cache)
			self::$_default_cache = $cache;
	}

	public static function load($name = false) {
		$database = self::$_default_database;
		$cache    = self::$_default_cache;

		if (!$database)
			trigger_error("No default database", E_USER_ERROR);

		if ($name) {
			if (!isset(self::$_resources[$name])) {
				$class = "{$name}_resource";

				if (class_exists($class))
					self::$_resources[$name] = new $class($database, $cache);
				else
					self::$_resources[$name] = new self($name, $database, $cache);
			}

			return self::$_resources[$name];
		} else {
			if (!self::$_default_resource)
				self::$_default_resource = new self(null, $database, $cache);

			return self::$_default_resource;
		}
	}

	public function get_all() {
		return $this->make_query([])->get_result();
	}

	public function get_result($args = []) {
		$defaults = $this->get_default_args();

		$args = array_merge($defaults, $args);
		$args = $this->filter_args($args);

		return $this->make_query($args)->get_result();
	}

	public function get_record($id, $rels = []) {
		if ($record = parent::get_record($id)) {
			foreach ($rels as $rel_name) {
				if ($relation = @$this->_relations[$rel_name]) {
					$resource = resource::load($relation->resource);

					if (method_exists($resource, $relation->method))
						$record[$rel_name] = call_user_func([$resource, $relation->method], $record->id);
				}
			}
		}

		return $record;
	}

	protected function get_default_args() {
		return [
			'limit'  => get_per_page(),
			'offset' => get_offset(get_page(), get_per_page()),
			'sort'   => get_sorting()
		];
	}

	protected function filter_args($args) {
		return $args;
	}

	protected function register_relation($name, $resource, $method) {
		$this->_relations[$name] = new object(compact($resource, $method));
	}
}
