<div class="modal card info" id="modal-card-languages">
	<header>
		<i class="fa fa-user"></i> <?php echo $user->alias; ?>
		<a class="cancel pull-right"><i class="fa fa-close"></i></a>
	</header>
	<strong><i class="fa fa-flag"></i> Languages</strong>
	<ul>
		<?php foreach ($user->languages as $language) : ?>
			<li><?php echo $language->name; ?></li>
		<?php endforeach; ?>
		<?php if ($user->languages->found > count($user->languages)) : ?>
			<li><em>more</em></li>
		<?php endif; ?>
	</ul>
</div>
