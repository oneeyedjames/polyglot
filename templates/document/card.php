<div class="card primary">
	<header>
		<a href="document/<?php echo $document->id; ?>"><?php echo $document->title; ?></a>
	</header>
	<p><?php echo $document->descrip; ?></p>
	<strong>Translations</strong>
	<ul>
		<?php foreach ($document->project->languages as $language) :
			if ($language->id != $document->project->default_language_id) :
				$url = "document/$document->id/translation/$language->id"; ?>
			<li>
				<?php if (isset($document->translations[$language->id])) :
					$translation = $document->translations[$language->id]; ?>
					<a href="<?php echo $url; ?>"><?php echo $translation->title; ?></a>
					<div class="row">
						<div class="col-xs-6"><i class="fa fa-flag"></i>  <?php echo $language->name; ?></div>
						<div class="col-xs-6"><i class="fa fa-user"></i> <?php echo $translation->user->name; ?></div>
					</div>
				<?php else : ?>
					<a href="<?php echo $url; ?>" class="btn sm text success">
						<i class="fa fa-plus"></i> Add Translation
					</a>
					<div><i class="fa fa-flag"></i> <?php echo $language->name; ?></div>
				<?php endif; ?>
			</li>
		<?php endif; endforeach; ?>
	</ul>
	<footer>
		<a class="btn primary" href="document/<?php echo $document->id; ?>/form">
			<i class="fa fa-edit"></i> Edit
		</a>
		<a class="btn danger">
			<i class="fa fa-trash"></i> Delete
		</a>
	</footer>
</div>
