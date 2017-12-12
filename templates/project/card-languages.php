<div class="card info">
	<header>
		<i class="fa fa-flag"></i> Languages
		<a href="project/<?php echo $project->id; ?>/form-language" target="#modal-card"
			class="pull-right" data-action="modal" data-target="#modal-form-language">
			<i class="fa fa-plus"></i> Add
		</a>
	</header>
	<ul>
		<li>
			<strong><?php echo $project->default_language->name; ?></strong>
			<em class="pull-right">Default</em>
			<div><?php echo $project->default_language->code; ?></div>
		</li>
		<?php foreach ($project->languages as $language) :
			if ($language->id != $project->default_language_id) : ?>
			<li>
				<strong><?php echo $language->name; ?></strong>
				<a class="btn sm text danger pull-right"
					data-action="submit" data-target="#remove-language-form"
					data-input-language="<?php echo $language->id; ?>">
					<i class="fa fa-minus"></i> Remove
				</a>
				<div><?php echo $language->code; ?></div>
			</li>
		<?php endif; endforeach; ?>
	</ul>
    <form action="project/<?php echo $project->id; ?>/remove-language" method="POST" id="remove-language-form"
    	data-confirm="Are you sure you want to remove this language from the project?">
    	<?php $nonce = $this->create_nonce('remove-language', 'project'); ?>
    	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
    	<input type="hidden" name="language">
    </form>
</div>
