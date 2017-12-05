<div class="card">
	<header><i class="fa fa-file"></i> Document</header>

	<strong>Project</strong>
	<p><a href="project/<?php echo $document->project->id; ?>"><?php echo $document->project->title; ?></a></p>

	<strong>Language</strong>
	<p><?php echo $document->language->name; ?></p>

	<strong>Author</strong>
	<p><?php echo $document->user->name; ?></p>

	<strong>Created</strong>
	<p><?php echo $document->created; ?></p>

	<?php if (!$document->revision) : ?>
		<strong>Updated</strong>
		<p><?php echo $document->updated; ?></p>
	<?php endif; ?>

	<?php if ($document->id) : ?>
		<footer>
			<a href="document/<?php echo $document->id; ?>/form" title="Edit" class="btn primary">
				<i class="fa fa-edit"></i> Edit
			</a>
			<a class="btn danger">
				<i class="fa fa-trash"></i> Delete
			</a>
		</footer>
	<?php endif; ?>
</div>
