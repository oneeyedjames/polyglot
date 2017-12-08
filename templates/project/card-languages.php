<div class="card info">
	<header>
		<i class="fa fa-flag"></i> Languages
		<a href="project/<?php echo $project->id; ?>/form-language" target="#modal-form"
			class="pull-right" data-action="modal" data-target="#modal-form-language">
			<i class="fa fa-plus"></i> Add
		</a>
	</header>
	<ul>
		<?php foreach ($project->languages as $language) : ?>
			<li>
				<strong><?php echo $language->name; ?></strong>
				<?php if ($language->id == $project->default_language_id) : ?>
					<em class="pull-right">Default</em>
				<?php else : ?>
					<a class="btn sm text danger pull-right"
						data-action="submit" data-target="#remove-language-form"
						data-input-language="<?php echo $language->id; ?>">
						<i class="fa fa-minus"></i> Remove
					</a>
				<?php endif; ?>
				<div><?php echo $language->code; ?></div>
			</li>
		<?php endforeach; ?>
	</ul>
    <form id="remove-language-form" action="project/<?php echo $project->id; ?>/remove-language" method="POST"
    	data-confirm="Are you sure you want to remove this language from the project?">
    	<?php $nonce = $this->create_nonce('remove-language', 'project'); ?>
    	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
    	<input type="hidden" name="language">
    </form>
</div>
