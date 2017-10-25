<?php

class permission_controller extends controller {
    public function __construct($database, $cache = null) {
		parent::__construct('permission', $database, $cache);
	}

    public function index_view($vars) {
        global $url_schema;

        $vars['resources'] = $url_schema->resources;

        return $vars;
    }
}
