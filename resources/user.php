<?php

class user_resource extends resource {
	public function __construct($database, $cache = false) {
		parent::__construct('user', $database, $cache);
	}

	protected function create_reset_token($email) {
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

    protected function reset_password($reset_token, $password, $password_confirm) {
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
}
