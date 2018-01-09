<?php $nonce = $this->create_nonce('add-language', 'user'); ?>
<form action="user/<?php echo $user->id; ?>/add-language" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<div class="modal card primary" id="modal-form-language">
		<header>
			<i class="fa fa-user"></i> <?php echo $user->alias; ?>
			<a class="pull-right cancel"><i class="fa fa-close"></i></a>
		</header>

		<label><i class="fa fa-flag"></i> Language</label>
		<select name="language">
			<?php foreach ($languages as $language) : ?>
				<option value="<?php echo $language->id; ?>"><?php echo $language->name; ?></option>
			<?php endforeach; ?>
		</select>

		<footer class="btns">
			<button type="button" class="btn cancel">Cancel</button>
			<button type="submit" class="btn primary">Save</button>
		</footer>
	</div>
</form>
