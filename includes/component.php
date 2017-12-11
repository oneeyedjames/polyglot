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

	foreach (glob(ASSET_PATH . '/sql/*-schema.sql') as $glob) {
		$source = file_get_contents(realpath($glob));
		$database->execute($source);
	}

	setup_languages($database);
	setup_admin_user($database);
	setup_user_roles($database);

	$database->execute('DELETE FROM session WHERE expire < NOW()');

	$tables    = json_decode(file_get_contents(ASSET_PATH . '/json/tables.json'));
	$bridges   = json_decode(file_get_contents(ASSET_PATH . '/json/bridges.json'));
	$relations = json_decode(file_get_contents(ASSET_PATH . '/json/relations.json'));

	foreach ($tables as $table)
		$database->add_table($table);

	foreach ($bridges as $bridge)
		$database->add_table($bridge, null);

	foreach ($relations as $rel_name => $rel_meta)
		$database->add_relation($rel_name, $rel_meta->ptable, $rel_meta->ftable, $rel_meta->fkey);

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

	$resources = json_decode(file_get_contents(ASSET_PATH . '/json/resources.json'), true);
	$actions = json_decode(file_get_contents(ASSET_PATH . '/json/actions.json'));
	$views = json_decode(file_get_contents(ASSET_PATH . '/json/views.json'));

	$res_actions = json_decode(file_get_contents(ASSET_PATH . '/json/resource-actions.json'));
	$res_views = json_decode(file_get_contents(ASSET_PATH . '/json/resource-views.json'));
	foreach ($resources as $resource => $alias)
		$url_schema->add_resource($resource, $alias);

	foreach ($actions as $action)
		$url_schema->add_action($action);

	foreach ($views as $view)
		$url_schema->add_view($view);

	foreach ($res_actions as $resource => $actions) {
		foreach ($actions as $action)
			$url_schema->add_action($action, $resource);
	}

	foreach ($res_views as $resource => $views) {
		foreach ($views as $view)
			$url_schema->add_view($view, $resource);
	}

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

function setup_languages($database) {
	if ($result = $database->query("SELECT * FROM `language`")) {
		if ($result->found == 0) {
			$sql = file_get_contents(ASSET_PATH . '/sql/language-data.sql');
			$database->execute($sql);
		}
	}

	return false;
}

function setup_admin_user($database) {
	if ($result = $database->query("SELECT * FROM `user`")) {
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

	if ($perms = $database->query("SELECT * FROM `permission`")) {
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

			$values = implode(', ', array_fill(0, count($grant_data) / 2, '(?, ?)'));

			$sql = "INSERT INTO `role_permission_map` (`role_id`, `permission_id`) VALUES $values";

			$database->execute($sql, $grant_data);
		}
	}

	return true;
}
