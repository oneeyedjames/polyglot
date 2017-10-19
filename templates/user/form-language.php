<?php $nonce = $this->create_nonce('save', 'user'); ?>
<form action="user/<?php echo $user->id; ?>/save" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<header>Edit User</header>

	<h3><i class="fa fa-user"></i> User</h3>
	<strong><?php echo $user->name; ?></strong>

	<h3><i class="fa fa-flag"></i> Languages</h3>
	<div class="checkbox">
		<?php foreach ($languages as $language) : ?>
			<label>
				<input type="checkbox" name="languages[]" value="<?php echo $language->id; ?>"<?php checked(isset($user->languages[$language->id])); ?>>
				<?php echo $language->name; ?>
			</label>
		<?php endforeach; ?>
	</div>

	<footer style="text-align: right;">
		<button type="button" class="btn cancel">Cancel</button>
		<button type="submit" class="btn blue">Save</button>
	</footer>
</form>
