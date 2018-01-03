<?php

if (isset($user->id))
	$url = "user/$user->id";
else
	$url = 'users';

$url .= '/save';

$nonce = $this->create_nonce('save', 'user');

?>
<form action="<?php echo $url; ?>" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<div class="modal card primary" id="modal-form-meta">
		<header>Edit User</header>

		<label>Name</label>
		<input type="text" name="user[name]" value="<?php echo $user->name; ?>">

		<label>Email Address</label>
		<input type="text" name="user[email]" value="<?php echo $user->email; ?>">

		<footer>
			<button type="submit" class="btn primary">Save</button>
			<button type="button" class="btn cancel">Cancel</button>
		</footer>
	</div>
</form>
