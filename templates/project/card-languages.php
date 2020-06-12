<div class="modal card info" id="modal-card-languages">
	<header>
		<i class="fa fa-folder-open"></i> <?php echo $project->title; ?>
		<a href="#" class="cancel pull-right"><i class="fa fa-close"></i></a>
	</header>
	<strong><i class="fa fa-flag"></i> Languages</strong>
	<ul>
		<?php foreach ($project->languages as $language) : ?>
			<li><?php echo $language->name; ?></li>
		<?php endforeach; ?>
		<?php if ($project->languages->found > count($project->languages)) : ?>
			<li><em>more</em></li>
		<?php endif; ?>
	</ul>
</div>
