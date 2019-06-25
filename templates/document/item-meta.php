<div class="card info">
	<header><i class="fa fa-file"></i> Document</header>

	<strong><i class="fa fa-sitemap"></i> Project</strong>
	<p><a href="project/<?php echo $document->project->id; ?>"><?php echo $document->project->title; ?></a></p>

	<strong><i class="fa fa-flag"></i> Language</strong>
	<p><?php echo $document->language->name; ?></p>

	<strong><i class="fa fa-user"></i> Author</strong>
	<p><?php echo $document->user->alias; ?></p>

	<strong><i class="fa fa-calendar"></i> Created</strong>
	<p><?php echo $document->created; ?></p>

	<?php if (!$document->revision) : ?>
		<strong><i class="fa fa-calendar"></i> Updated</strong>
		<p><?php echo $document->updated; ?></p>
	<?php endif; ?>
</div>
