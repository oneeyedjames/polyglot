<?php

class resource extends resource_base {
	private static $_default_resource = false;
	private static $_default_database = false;
	private static $_default_cache    = false;

	private static $_resources = [];

	private $_parent_relations = [];
	private $_child_relations  = [];

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

	public function get_result($args = [], $rels = []) {
		$defaults = $this->get_default_args();

		$args = array_merge($defaults, $args);
		$args = $this->filter_args($args);

		$result = $this->make_query($args)->get_result();
		$record_ids = $result->map(function($record) {
			return $record->id;
		})->toArray();

		foreach ($rels as $rel_name) {
			if ($relation = @$this->_parent_relations[$rel_name]) {
				$resource = resource::load($relation->resource);

				$rel_ids = $result->map(function($record) use ($relation) {
					return $record[$relation->field];
				})->toArray();

				$rel_result = $resource->make_query([
					'args' => ['id' => $rel_ids]
				])->get_result();

				$result->walk(function(&$record) use ($relation, $rel_name, $rel_result) {
					foreach ($rel_result as $rel_record) {
						if ($record[$relation->field] == $rel_record->id) {
							$record[$rel_name] = $rel_record;
							break;
						}
					}
				});
			} elseif ($relation = @$this->_child_relations[$rel_name]) {
				$resource = resource::load($relation->resource);

				if (method_exists($resource, $relation->method)) {
					$rel_result = call_user_func([$resource, $relation->method], $record_ids);

					$result->walk(function(&$record) use ($rel_name, $rel_result) {
						$matches = [];

						foreach ($rel_result as $rel_record) {
							if ($record->id == $rel_record["{$this->name}_id"])
								$matches[] = $rel_record;
						}

						$record[$rel_name] = new database_result($matches, count($matches));
					});
				}
			}
		}

		return $result;
	}

	public function get_record($id, $rels = []) {
		if ($record = parent::get_record($id)) {
			foreach ($rels as $rel_name) {
				if ($relation = @$this->_parent_relations[$rel_name]) {
					$resource = resource::load($relation->resource);

					$record[$rel_name] = $resource->get_record($record[$relation->field]);
				} elseif ($relation = @$this->_child_relations[$rel_name]) {
					$resource = resource::load($relation->resource);

					if (method_exists($resource, $relation->method))
						$record[$rel_name] = call_user_func([$resource, $relation->method], $record->id);
				}
			}
		}

		return $record;
	}

	protected function get_default_args() {
		$defaults = [
			'limit'  => get_per_page(),
			'offset' => get_offset(get_page(), get_per_page())
		];

		if ($sort = get_sorting())
			$defaults['sort'] = $sort;

		return $defaults;
	}

	protected function filter_args($args) { return $args; }

	protected function register_parent_relation($name, $resource, $field) {
		$this->_parent_relations[$name] = new object(compact('resource', 'field'));
	}

	protected function register_child_relation($name, $resource, $method) {
		$this->_child_relations[$name] = new object(compact('resource', 'method'));
	}
}
