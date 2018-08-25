<?php

class resource extends resource_base {
	private static $_default_resource = false;
	private static $_default_database = false;
	private static $_default_cache    = false;

	private static $_resources = [];

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

	public function get_result($limit = false, $offset = false) {
		if ($limit === false)
			$limit = get_per_page();

		if ($offset === false)
			$offset = get_offset(get_page(), $limit);

		$args = compact('limit', 'offset');

		if ($sort = get_sorting())
			$args['sort'] = $sort;
		elseif ($sort = $this->get_default_sorting())
			$args['sort'] = $sort;

		$this->filter_result_args($args);

		return $this->make_query($args)->get_result();
	}

	public function get_default_sorting() { return false; }

	public function filter_result_args(&$args) {}
}
