<div class="card info">
	<header><i class="fa fa-clock-o"></i> Revisions</header>
	<ul>
		<?php foreach ($document->revisions as $revision) : ?>
			<li>
				<?php if ($revision->id == $document->id) : ?><strong><?php endif; ?>
				<a href="document/<?php echo $revision->id; ?>"><?php echo $revision->created; ?></a>
				<?php if ($revision->id == $document->id) : ?></strong><?php endif; ?>
				<div><i class="fa fa-user"></i> <?php echo $revision->user->name; ?></div>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
