<?php

$url = (isset($role->id) ? "role/$role->id" : 'roles') . '/save';

$nonce = $this->create_nonce('save', 'role');

?>
<form action="<?php echo $url; ?>" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<div class="modal card primary" id="modal-form-meta">
		<header>Edit Role</header>

		<label>Title</label>
		<input type="text" name="role[title]" value="<?php echo $role->title; ?>">

		<label>Description</label>
		<textarea name="role[description]" class="form-control"><?php echo $role->descrip; ?></textarea>

		<footer>
			<button type="submit" class="btn primary">Save</button>
			<button type="button" class="btn cancel">Cancel</button>
		</footer>
	</div>
</form>
