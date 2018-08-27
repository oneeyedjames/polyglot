<?php

class user_resource extends resource {
	public function __construct($database, $cache = false) {
		parent::__construct('user', $database, $cache);
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



	public function get_record($user_id, $rels = []) {
		if ($user = parent::get_record($user_id)) {
			if (in_array('project', $rels))
				$user->projects = resource::load('project')->get_by_user_id($user_id);

			if (in_array('language', $rels))
				$user->languages = resource::load('language')->get_by_user_id($user_id);
		}

		return $user;
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
