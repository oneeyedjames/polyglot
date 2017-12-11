<?php $nonce = $this->create_nonce('save', 'user'); ?>
<form action="user/<?php echo $user->id; ?>/save" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<div class="modal card primary" id="modal-form-languages">
		<header><i class="fa fa-user"></i> <?php echo $user->name; ?></header>

		<strong><i class="fa fa-flag"></i> Languages</strong>

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
			<button type="submit" class="btn primary">Save</button>
		</footer>
	</div>
</form>