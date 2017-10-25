<?php

$url = (isset($role->id) ? "permission/$permission->id" : 'permissions') . '/save';

$nonce = $this->create_nonce('save', 'permission');

?>
<form action="<?php echo $url; ?>" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<header>Edit Permission</header>

	<label>Resource</label>
	<select name="permission[resource]" class="form-control">
    </select>

	<label>Action</label>
	<select name="permission[action]" class="form-control">
    </select>

	<footer>
		<button type="submit" class="btn blue">Save</button>
		<button type="button" class="btn cancel">Cancel</button>
	</footer>
</form>
