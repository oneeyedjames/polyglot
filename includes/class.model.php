<?php

use PHPunk\Component\model as model_base;
use PHPunk\Database\result;
use PHPunk\Database\record;
use PHPunk\Util\object;

class model extends model_base {
	private static $_default_database = false;
	private static $_default_cache    = false;
	private static $_default_model    = false;

	private static $_models = [];

	private $_parent_relations = [];
	private $_child_relations  = [];

	public static function init($database = false, $cache = false) {
		if (!self::$_default_database)
			self::$_default_database = $database;

		if (!self::$_default_cache)
			self::$_default_cache = $cache;
	}

	public static function load($resource = false) {
		$database = self::$_default_database;
		$cache    = self::$_default_cache;

		if (!$database)
			trigger_error("No default database", E_USER_ERROR);

		if ($resource) {
			if (!isset(self::$_models[$resource])) {
				$class = "{$resource}_model";

				if (class_exists($class))
					self::$_models[$resource] = new $class($database, $cache);
				else
					self::$_models[$resource] = new self($resource, $database, $cache);
			}

			return self::$_models[$resource];
		} else {
			if (!self::$_default_model)
				self::$_default_model = new self(null, $database, $cache);

			return self::$_default_model;
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
				$model = model::load($relation->resource);

				$rel_ids = $result->map(function($record) use ($relation) {
					return $record[$relation->field];
				})->toArray();

				$rel_result = $model->make_query([
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
				$model = model::load($relation->resource);

				if (method_exists($model, $relation->method)) {
					$rel_result = call_user_func([$model, $relation->method], $record_ids);

					$result->walk(function(&$record) use ($rel_name, $rel_result) {
						$matches = [];

						foreach ($rel_result as $rel_record) {
							// TODO refactor this foreign key
							if ($record->id == $rel_record["{$this->resource}_id"])
								$matches[] = $rel_record;
						}

						$record[$rel_name] = new result($matches, count($matches));
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
					$model = model::load($relation->resource);

					$record[$rel_name] = $model->get_record($record[$relation->field]);
				} elseif ($relation = @$this->_child_relations[$rel_name]) {
					$model = model::load($relation->resource);

					if (method_exists($model, $relation->method))
						$record[$rel_name] = call_user_func([$model, $relation->method], $record->id);
				}
			}
		}

		return $record;
	}

	// TODO backport to PHPunk
	public function create_record($data = []) {
		if (is_numeric($data)) $data = ['id' => $data];
		return new record($data, $this->resource);
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
