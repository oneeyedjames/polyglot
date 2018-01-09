<?php

$url = (isset($role->id) ? "role/$role->id" : 'roles') . '/save';

$nonce = $this->create_nonce('save', 'role');

?>
<form action="<?php echo $url; ?>" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<div class="modal card primary" id="modal-form-meta">
		<header>
			<i class="fa fa-users"></i> Edit Role
			<a class="pull-right cancel"><i class="fa fa-close"></i></a>
		</header>

		<label>Title</label>
		<input type="text" name="role[title]" value="<?php echo $role->title; ?>">

		<label>Description</label>
		<textarea name="role[description]" class="form-control"><?php echo $role->descrip; ?></textarea>

		<footer class="btns">
			<button type="button" class="btn cancel">Cancel</button>
			<button type="submit" class="btn primary">Save</button>
		</footer>
	</div>
</form>
