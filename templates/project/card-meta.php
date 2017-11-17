<div class="card">
	<header><i class="fa fa-info-circle"></i> Project Details</header>

	<strong>Languages</strong>
	<a class="txt-btn sm green pull-right"
		href="project/<?php echo $project->id; ?>/form-language"
		data-action="modal" data-target="#modal-form">
		<i class="fa fa-plus"></i> Add
	</a>
	<ul>
		<?php foreach ($project->languages as $language) : ?>
			<li>
				<span><?php echo $language->name; ?></span>
				<small>(<?php echo $language->code; ?>)</small>
				<?php if ($language->id == $project->default_language_id) : ?>
					<em class="txt-btn gray pull-right">Default</em>
				<?php else : ?>
					<a class="txt-btn red pull-right"
						data-action="submit" data-target="#remove-language-form"
						data-input-language="<?php echo $language->id; ?>">
						<i class="fa fa-minus"></i> Remove
					</a>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>

	<strong>Users</strong>
	<a class="txt-btn sm green pull-right"
		href="project/<?php echo $project->id; ?>/form-user"
		data-action="modal" data-target="#modal-form">
		<i class="fa fa-plus"></i> Add
	</a>
	<ul>
		<?php foreach ($project->users as $user) : ?>
			<li>
				<span><?php echo $user->name;?></span>
				<small>(<?php echo $user->role->title; ?>)</small>
				<a class="txt-btn red pull-right"
					data-action="submit" data-target="#remove-user-form"
					data-input-user="<?php echo $user->id; ?>">
					<i class="fa fa-minus"></i> Remove
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
<form id="remove-language-form" action="project/<?php echo $project->id; ?>/remove-language" method="POST"
	data-confirm="Are you sure you want to remove this language from the project?">
	<?php $nonce = $this->create_nonce('remove-language', 'project'); ?>
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<input type="hidden" name="language">
</form>
<form id="remove-user-form" action="project/<?php echo $project->id; ?>/remove-user" method="POST"
	data-confirm="Are you sure you want to remove this user from the project?">
	<?php $nonce = $this->create_nonce('remove-user', 'project'); ?>
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<input type="hidden" name="user">
</form>
