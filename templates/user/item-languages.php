<div class="card info">
	<header>
		<i class="fa fa-flag"></i> Languages
		<a href="user/<?php echo $user->id; ?>/form-language" target="#modal-card"
			class="pull-right" data-action="modal" data-target="#modal-form-language">
			<i class="fa fa-plus"></i> Add
		</a>
	</header>
	<ul>
		<?php foreach ($user->languages as $language) : ?>
			<li>
				<strong><?php echo $language->name; ?></strong>
				<a class="btn sm text danger pull-right"
					data-action="submit" data-target="#remove-language-form"
					data-input-language="<?php echo $language->id; ?>">
					<i class="fa fa-minus"></i> Remove
				</a>
				<div><?php echo $language->code; ?></div>
			</li>
		<?php endforeach; ?>
	</ul>
	<form action="user/<?php echo $user->id; ?>/remove-language" method="POST" id="remove-language-form"
		data-confirm="Are you sure you want to remove this language from the user?">
		<?php $nonce = $this->create_nonce('remove-language', 'user'); ?>
		<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
		<input type="hidden" name="language">
	</form>
</div>
