<div class="card">
	<header><i class="fa fa-clock-o"></i> Revisions</header>
	<ul>
		<?php foreach ($document->revisions as $revision) : ?>
			<li>
				<?php if ($revision->id == $document->id) : ?><strong><?php endif; ?>
				<a href="document/<?php echo $revision->id; ?>"><?php echo $revision->created; ?></a>
				<div><?php echo $revision->user->name; ?></div>
				<?php if ($revision->id == $document->id) : ?></strong><?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
