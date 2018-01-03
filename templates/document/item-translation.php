<?php $master = $document->master ?: $document; ?>
<div class="card info">
	<header><i class="fa fa-flag"></i> Translations</header>
	<ul>
		<?php if ($document->master) : ?>
			<li>
				<a href="document/<?php echo $document->master->id; ?>">
					<?php echo $document->master->title; ?>
				</a>
				<div class="row">
					<div class="col-xs-6"><i class="fa fa-flag"></i>  <?php echo $document->master->language->name; ?></div>
					<div class="col-xs-6"><i class="fa fa-user"></i> <?php echo $document->master->user->alias; ?></div>
				</div>
			</li>
		<?php endif; foreach ($document->project->languages as $language) :
			if (!in_array($language->id, [$document->language->id, $master->language->id])) : ?>
			<li>
				<?php if ($translation = $document->translations[$language->code]) : ?>
					<a href="document/<?php echo $master->id; ?>/translation/<?php echo $language->id; ?>">
						<?php echo $translation->title; ?>
					</a>
					<div class="row">
						<div class="col-xs-6"><i class="fa fa-flag"></i> <?php echo $language->name; ?></div>
						<div class="col-xs-6"><i class="fa fa-user"></i> <?php echo $translation->user->alias; ?></div>
					</div>
				<?php else : ?>
					<a href="document/<?php echo $master->id; ?>/form/translation/<?php echo $language->id; ?>"
						class="btn sm text success">
						<i class="fa fa-plus"></i> Add Translation
					</a>
					<div><i class="fa fa-flag"></i> <?php echo $language->name; ?></div>
				<?php endif; ?>
			</li>
		<?php endif; endforeach; ?>
	</ul>
</div>
