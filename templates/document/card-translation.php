<?php $master = $document->master ?: $document; ?>
<div class="card">
	<header><i class="fa fa-flag"></i> Translations</header>
	<ul>
		<?php if ($document->master) : ?>
			<li>
				<a href="document/<?php echo $document->master->id; ?>">
					<?php echo $document->master->title; ?>
				</a>
				<div>in <?php echo $document->master->language->name; ?></div>
				<div>by <?php echo $document->master->user->name; ?></div>
			</li>
		<?php endif; foreach ($document->project->languages as $language) :
			if (!in_array($language->id, array($document->language->id, $master->language->id))) : ?>
			<li>
				<?php if ($translation = $document->translations[$language->code]) : ?>
					<a href="document/<?php echo $master->id; ?>/translation/<?php echo $language->id; ?>">
						<?php echo $translation->title; ?>
					</a>
					<div>in <?php echo $language->name; ?></div>
					<div>by <?php echo $translation->user->name; ?></div>
				<?php else : ?>
					<a href="document/<?php echo $master->id; ?>/form/translation/<?php echo $language->id; ?>">
						Add Translation
					</a>
					<div>in <?php echo $language->name; ?></div>
				<?php endif; ?>
			</li>
		<?php endif; endforeach; ?>
	</ul>
</div>
