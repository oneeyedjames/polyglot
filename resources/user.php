<?php

class user_resource extends resource {
	public function __construct($database, $cache = false) {
		parent::__construct('user', $database, $cache);

		$this->register_child_relation('projects',  'project',  'get_by_user_id');
		$this->register_child_relation('languages', 'language', 'get_by_user_id');
	}

	public function create_session($user_id, $token, $expire) {
		$sql = 'INSERT INTO session (user_id, token, expire) VALUES (?, ?, ?)';

		return $this->execute($sql, intval($user_id), $token, date('Y-m-d H:i:s', $expire));
	}

	public function create_reset_token($email) {
        $user = $this->make_query([
            'limit' => 1,
            'args'  => compact('email')
        ])->get_result()->first;

        if ($user) {
            $user->reset_token  = create_nonce(16);
            $user->reset_expire = date('Y-m-d H:i:s', time() + 1800);

            $this->put_record($user);

            return $user;
        }

        return false;
    }

    public function reset_password($reset_token, $password, $password_confirm) {
        if (!empty($password) && $password == $password_confirm) {
            $user = $this->make_query([
                'limit' => 1,
                'args'  => compact('reset_token'),
            ])->get_result()->first;

            if ($user) {
                $valid = false;

                if (strtotime($user->reset_expire) <= time()) {
                    // TODO error, expired token
                } else {
                    $user->password = password_hash($password, PASSWORD_DEFAULT);
                    $valid = true;
                }

                $user->reset_token  = null;
                $user->reset_expire = null;

                $this->put_record($user);

                if ($valid)
                    return $user;
            }
        } else {
            // TODO error, invalid password
        }

        return false;
    }



	public function add_project($user_id, $proj_id, $role_id) {
		$sql = 'INSERT INTO `user_project_map` (`user_id`, `project_id`, `role_id`) VALUES (?, ?, ?)';
		return $this->execute($sql, intval($user_id), intval($proj_id), intval($role_id));
	}

	public function remove_project($user_id, $proj_id) {
		$sql = 'DELETE FROM `user_project_map` WHERE `user_id` = ? AND `project_id` = ?';
		return $this->execute($sql, intval($user_id), intval($proj_id));
	}

	public function add_language($user_id, $lang_id) {
		$sql = 'INSERT INTO `user_language_map` (`user_id`, `language_id`) VALUES (?, ?)';
		return $this->execute($sql, intval($user_id), intval($lang_id));
	}

	public function remove_language($user_id, $lang_id) {
		$sql = 'DELETE FROM `user_language_map` WHERE `user_id` = ? AND `language_id` = ?';
		return $this->execute($sql, intval($user_id), intval($lang_id));
	}




	public function get_by_email($email) {
		return $this->make_query([
			'args'  => compact('email'),
			'limit' => 1
		])->get_result()->first;
	}

	public function get_by_project_id($proj_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['bridge'] = 'up_user';
		$args['args'] = ['up_project' => $proj_id];

		$users = $this->make_query($args)->get_result();

		if ($users->found) {
			$this->get_roles($users, $proj_id);
		}

		return $users;
	}

	public function get_by_language_id($lang_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['bridge'] = 'ul_user';
		$args['args'] = ['ul_language' => $lang_id];

		return $this->make_query($args, 'user')->get_result();
	}

	protected function get_projects($user_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['bridge'] = 'up_project';
		$args['args'] = ['up_user' => $user_id];

		$projects = $this->make_query($args, 'project')->get_result();
		$projects->walk(function(&$project) {
			$project->role = $this->make_query([
				'bridge' => 'up_role',
				'args'   => [
					'up_user'    => $user_id,
					'up_project' => $project->id
				]
			], 'role')->get_result()->first;
		});

		return $projects;
	}

	protected function get_languages($user_id, $limit = DEFAULT_PER_PAGE, $offset = 0) {
		$args = compact('limit', 'offset');
		$args['bridge'] = 'ul_language';
		$args['args'] = ['ul_user' => $user->id];

		return $this->make_query($args, 'language')->get_result();
	}

	protected function get_roles(&$users, $proj_id) {
		$user_ids = $users->map(function($user) {
			return $user->id;
		})->toArray();

		$roles = resource::load('role')->make_query([
			'bridge' => 'up_role',
			'args'   => [
				'up_user'    => $user_ids,
				'up_project' => $proj_id
			]
		])->get_result()->key_map(function($role) {
			return $role->user_id;
		});

		$users->walk(function(&$user) use ($roles) {
			$user->role = $roles[$user->id];
		});
	}
}
