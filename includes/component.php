<?php

function init_database() {
	$mysql = new object();

	if (is_file($config = CONFIG_PATH . '/mysql.php'))
		require $config;

	$host = $mysql->hostname('localhost');
	$user = $mysql->username;
	$pass = $mysql->password;
	$db   = $mysql->database('polyglot');

	$mysql = new mysqli($host, $user, $pass);

	if ($mysql->connect_errno) {
		error_log($mysql->connect_error);
		return false;
	}

	$mysql->set_charset('utf8');

	if ($result = $mysql->query("SHOW DATABASES LIKE '$db'")) {
		if (0 == $result->num_rows) {
			if (!$mysql->query("CREATE DATABASE `$db`"))
				trigger_error($mysql->error);
		}

		$result->close();
	}

	$mysql->select_db($db);

	$database = new database_schema($mysql);

	$schema = get_database_schema();
	foreach ($schema as $create)
		$database->execute($create);

	setup_admin_user($database);
	setup_user_roles($database);

	$database->execute('DELETE FROM session WHERE expire < NOW()');

	$database->add_table('user');
	$database->add_table('role');
	$database->add_table('permission');

	$database->add_table('project');
	$database->add_table('language');
	$database->add_table('document');
	$database->add_table('list');
	$database->add_table('term');

	$database->add_relation('project_document', 'project', 'document', 'project_id');
	$database->add_relation('project_list',     'project', 'list',     'project_id');

	$database->add_relation('language_document', 'language', 'document', 'language_id');
	$database->add_relation('language_term',     'language', 'term',     'language_id');

	$database->add_relation('list_term', 'list', 'term', 'list_id');

	$database->add_relation('user_document', 'user', 'document', 'user_id');
	$database->add_relation('user_list',     'user', 'list',     'user_id');
	$database->add_relation('user_term',     'user', 'term',     'user_id');

	$database->add_table('project_language_map', null);
	$database->add_relation('pl_project',  'project',  'project_language_map', 'project_id');
	$database->add_relation('pl_language', 'language', 'project_language_map', 'language_id');

	$database->add_table('user_project_map', null);
	$database->add_relation('up_user',    'user',    'user_project_map', 'user_id');
	$database->add_relation('up_project', 'project', 'user_project_map', 'project_id');
	$database->add_relation('up_role',    'role',    'user_project_map', 'role_id');

	$database->add_table('user_language_map', null);
	$database->add_relation('ul_user',     'user',     'user_language_map', 'user_id');
	$database->add_relation('ul_language', 'language', 'user_language_map', 'language_id');

	$database->add_table('role_permission_map', null);
	$database->add_relation('rp_role',       'role',       'role_permission_map', 'role_id');
	$database->add_relation('rp_permission', 'permission', 'role_permission_map', 'permission_id');

	return $database;
}

function init_cache() {
	$memcached = new object();

	if (is_file($config = CONFIG_PATH . '/memcached.php'))
		require $config;

	$host = $memcached->host('localhost');
	$port = $memcached->port(11211);

	$memcached = new Memcached();
	$memcached->addServer($host, $port);

	return new cache($memcached);
}

function init_url() {
	$url_path = $_SERVER['REQUEST_URI'];

	if (($pos = strpos($url_path, '?')) !== false)
		$url_path = substr($url_path, 0, $pos);
	elseif (($pos = strpos($url_path, '#')) !== false)
		$url_path = substr($url_path, 0, $pos);

	$url_schema = new url_schema($_SERVER['HTTP_HOST']);

	$url_schema->add_action('login');
	$url_schema->add_action('logout');

	$url_schema->add_view('login-form');

	$url_schema->add_resource('user',     'users');
	$url_schema->add_resource('role',     'roles');
	$url_schema->add_resource('project',  'projects');
	$url_schema->add_resource('language', 'languages');
	$url_schema->add_resource('document', 'documents');
	$url_schema->add_resource('list',     'lists');
	$url_schema->add_resource('term',     'terms');

	$url_schema->add_action('add-language',    'project');
	$url_schema->add_action('remove-language', 'project');
	$url_schema->add_action('add-user',        'project');
	$url_schema->add_action('remove-user',     'project');

	$url_schema->add_action('add-permission',    'role');
	$url_schema->add_action('remove-permission', 'role');

	$url_schema->add_view('form-meta',     'user');
	$url_schema->add_view('form-project',  'user');
	$url_schema->add_view('form-language', 'user');
	$url_schema->add_view('form-meta',     'role');
	$url_schema->add_view('form-permission', 'role');
	$url_schema->add_view('form-meta',     'project');
	$url_schema->add_view('form-language', 'project');
	$url_schema->add_view('form-user',     'project');
	$url_schema->add_view('form-meta',     'language');
	$url_schema->add_view('card-projects', 'language');
	$url_schema->add_view('card-users',    'language');
	$url_schema->add_view('form-meta',     'document');
	$url_schema->add_view('form-meta',     'list');
	$url_schema->add_view('form-meta',     'term');

	$url_schema->add_view('card-languages', 'project');
	$url_schema->add_view('card-users',     'project');

	$url_params = $url_schema->parse_path($url_path);

	foreach ($url_params as $key => $value)
		$_GET[$key] = $_REQUEST[$key] = $value;

	$_GET['ajax'] = $_REQUEST['ajax'] = @$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';

	return $url_schema;
}

function init_session() {
	if ($token = @$_COOKIE['user_token']) {
		if ($session = get_session($token)) {
			if ($user = get_user($session->user_id)) {
				if ($user->verify_token($token))
					return intval($user->id);
				else
					force_login();
			} else {
				force_login();
			}
		} else {
			force_login();
		}
	} elseif (IS_LOGIN) {
		return 0;
	} else {
		force_login();
	}
}

function init_language() {
	static $regex = '/((([A-Za-z0-9]+-?)+\s*,?\s*)+);\s*q\s*=\s*(1|0\.[0-9]+)/';

	$header = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

	preg_match_all($regex, $header, $matches, PREG_SET_ORDER);

	$lang = array();

	foreach ($matches as $match) {
		foreach (explode(',', $match[1]) as $slug) {
			$lang[$match[4]][] = trim($slug);
		}
	}

	krsort($lang, SORT_NUMERIC);

	return $lang;
}

function force_login() {
	setcookie('user_token', null, time() - 300);
	header('Location: /login-form');
	exit;
}

function error_page($code) {
	http_response_code($code);

	include TEMPLATE_PATH . '/error/header.php';
	include TEMPLATE_PATH . "/error/$code.php";
	include TEMPLATE_PATH . '/error/footer.php';

	exit;
}

function get_database_schema() {
	$schema = new object();

	$schema->user = "CREATE TABLE IF NOT EXISTS `user` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`name` varchar(255) NOT NULL,
		`password` varchar(128) NOT NULL,
		`email` varchar(255) NOT NULL,
		`admin` tinyint(3) unsigned NOT NULL DEFAULT 0,
		PRIMARY KEY (`id`),
		UNIQUE KEY `email` (`email`)
	) DEFAULT CHARSET=utf8";

	$schema->session = "CREATE TABLE IF NOT EXISTS `session` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`user_id` int(10) unsigned NOT NULL,
		`token` varchar(100) NOT NULL,
		`expire` datetime NOT NULL,
		PRIMARY KEY (`id`),
		UNIQUE KEY `token` (`token`),
		KEY `user_id` (`user_id`)
	) DEFAULT CHARSET=utf8";

	$schema->role = "CREATE TABLE IF NOT EXISTS `role` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`title` tinytext NOT NULL,
		`descrip` text NULL,
		PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8";

	$schema->permission = "CREATE TABLE IF NOT EXISTS `permission` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`resource` varchar(128) NOT NULL,
		`action` varchar(128) NOT NULL,
		PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8";

	$schema->project = "CREATE TABLE IF NOT EXISTS `project` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`default_language_id` int(10) unsigned NOT NULL,
		`title` tinytext NOT NULL,
		`descrip` text NULL,
		PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8";

	$schema->language = "CREATE TABLE IF NOT EXISTS `language` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`code` varchar(10) NOT NULL,
		`name` tinytext NOT NULL,
		PRIMARY KEY (`id`),
		UNIQUE KEY `code` (`code`)
	) DEFAULT CHARSET=utf8";

	$schema->document = "CREATE TABLE IF NOT EXISTS `document` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`master_id` int(10) unsigned NOT NULL DEFAULT 0,
		`language_id` int(10) unsigned NOT NULL,
		`project_id` int(10) unsigned NOT NULL,
		`user_id` int(10) unsigned NOT NULL,
		`title` tinytext NOT NULL,
		`content` text NULL,
		`descrip` text NULL,
		`created` datetime NULL,
		`updated` datetime NULL,
		`revision` tinyint(3) unsigned NOT NULL DEFAULT 0,
		PRIMARY KEY (`id`),
		KEY `project_id` (`project_id`)
	) DEFAULT CHARSET=utf8";

	$schema->list = "CREATE TABLE IF NOT EXISTS `list` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`master_id` int(10) unsigned NOT NULL DEFAULT 0,
		`project_id` int(10) unsigned NOT NULL,
		`user_id` int(10) unsigned NOT NULL,
		`title` tinytext NOT NULL,
		`descrip` text NULL,
		`created` datetime NULL,
		`updated` datetime NULL,
		`revision` tinyint(3) unsigned NOT NULL DEFAULT 0,
		PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8";

	$schema->term = "CREATE TABLE IF NOT EXISTS `term` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`master_id` int(10) unsigned NOT NULL DEFAULT 0,
		`language_id` int(10) unsigned NOT NULL,
		`list_id` int(10) unsigned NOT NULL,
		`user_id` int(10) unsigned NOT NULL,
		`content` text NULL,
		`descrip` text NULL,
		`created` datetime NULL,
		`updated` datetime NULL,
		`revision` tinyint(3) unsigned NOT NULL DEFAULT 0,
		PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8";

	$schema->role_permission_map = "CREATE TABLE IF NOT EXISTS `role_permission_map` (
		`role_id` int(10) unsigned NOT NULL,
		`permission_id` int(10) unsigned NOT NULL,
		`granted` tinyint(3) unsigned NOT NULL,
		PRIMARY KEY (`role_id`,`permission_id`)
	) DEFAULT CHARSET=utf8";

	$schema->user_project_map = "CREATE TABLE IF NOT EXISTS `user_project_map` (
		`user_id` int(10) unsigned NOT NULL,
		`project_id` int(10) unsigned NOT NULL,
		`role_id` int(10) unsigned NOT NULL,
		PRIMARY KEY (`user_id`,`project_id`)
	) DEFAULT CHARSET=utf8";

	$schema->user_language_map = "CREATE TABLE IF NOT EXISTS `user_language_map` (
		`user_id` int(10) unsigned NOT NULL,
		`language_id` int(10) unsigned NOT NULL,
		PRIMARY KEY (`user_id`,`language_id`)
	) DEFAULT CHARSET=utf8";

	$schema->project_language_map = "CREATE TABLE IF NOT EXISTS `project_language_map` (
		`project_id` int(10) unsigned NOT NULL,
		`language_id` int(10) unsigned NOT NULL,
		PRIMARY KEY (`project_id`,`language_id`)
	) DEFAULT CHARSET=utf8";

	return $schema;
}

function setup_admin_user($database) {
	if ($result = $database->query("SELECT * FROM user")) {
		if ($result->found)
			return $result->first;

		$domain = $_SERVER['SERVER_NAME'];

		$admin = new object();

		if (is_file($config = CONFIG_PATH . '/admin.php'))
			require $config;

		$name     = $admin->name('Administrator');
		$password = $admin->password('Password1!');
		$email    = $admin->email('admin@polyglot.dev');

		$sql = 'INSERT INTO `user` (`name`, `password`, `email`, `admin`) VALUES (?, ?, ?, ?)';

		return $database->execute($sql, $name, password_hash($password, PASSWORD_DEFAULT), $email, 1);
	}

	return false;
}

function setup_user_roles($database) {
	$role_data = array(
		'Manager' => array(
			'project' => array('save', 'add-user', 'remove-user'),
			'document' => array('save')
		),
		'Editor' => array(
			'document' => array('save')
		),
		'Translator' => array(
			'document' => array('save')
		)
	);

	if ($roles = $database->query("SELECT * FROM `role`")) {
		if (!$roles->found) {
			$values = implode(', ', array_fill(0, count($role_data), '(?)'));

			$sql = "INSERT INTO `role` (`title`) VALUES $values";

			$database->execute($sql, array_keys($role_data));

			$roles = $database->query("SELECT * FROM `role`");
		}
	} else {
		return false;
	}

	if ($perms = $database->query("SELECT * FROM permission")) {
		if (!$perms->found) {
			$perm_data = $all_perm_data = array();

			foreach ($role_data as $role_meta) {
				foreach ($role_meta as $resource => $actions) {
					foreach ($actions as $action) {
						$all_perm_data[$resource][] = $action;
					}

					$all_perm_data[$resource] = array_unique($all_perm_data[$resource]);
				}
			}

			foreach ($all_perm_data as $resource => $actions) {
				foreach ($actions as $action) {
					$perm_data[] = $resource;
					$perm_data[] = $action;
				}
			}

			$values = implode(', ', array_fill(0, count($perm_data) / 2, '(?, ?)'));

			$sql = "INSERT INTO `permission` (`resource`, `action`) VALUES $values";

			$database->execute($sql, $perm_data);

			$perms = $database->query("SELECT * FROM permission");
		}
	} else {
		return false;
	}

	if ($acl = $database->query("SELECT * FROM `role_permission_map`")) {
		if (!$acl->found) {
			$role_ids = $perm_ids = array();

			foreach ($roles as $role) {
				$role_ids[$role->title] = $role->id;
			}

			foreach ($perms as $perm) {
				$perm_ids["$perm->resource:$perm->action"] = $perm->id;
			}

			$grant_data = array();

			foreach ($role_data as $role_title => $perm_data) {
				$role_id = $role_ids[$role_title];

				foreach ($perm_data as $resource => $actions) {
					foreach ($actions as $action) {
						$perm_id = $perm_ids["$resource:$action"];

						$grant_data[] = $role_id;
						$grant_data[] = $perm_id;
					}
				}
			}

			$values = implode(', ', array_fill(0, count($grant_data) / 2, '(?, ?, 1)'));

			$sql = "INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `granted`) VALUES $values";

			$database->execute($sql, $grant_data);
		}
	}

	return true;
}
