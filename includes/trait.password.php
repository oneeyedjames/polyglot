<?php

trait password {
	protected abstract function make_query($args, $resource = false);
	protected abstract function get_record($id, $resource = false);
	protected abstract function put_record($record, $resource = false);

	protected function create_reset_token($email) {
		$user = $this->make_query([
			'limit' => 1,
			'args'  => compact('email')
		], 'user')->get_result()->first;

		if ($user) {
			$user->reset_token  = create_nonce(16);
			$user->reset_expire = date('Y-m-d H:i:s', time() + 1800);

			$this->put_record($user, 'user');

			return $user;
		}

		return false;
	}

	protected function reset_password($reset_token, $password, $password_confirm) {
		if (!empty($password) && $password == $password_confirm) {
			$user = $this->make_query([
				'limit' => 1,
				'args'  => compact('reset_token'),
			], 'user')->get_result()->first;

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

				$this->put_record($user, 'user');

				if ($valid)
					return $user;
			}
		} else {
			// TODO error, invalid password
		}

		return false;
	}
}
