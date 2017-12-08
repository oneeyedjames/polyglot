<div class="card primary">
	<header>
		<a href="document/<?php echo $document->id; ?>"><?php echo $document->title; ?></a>
	</header>
	<p><?php echo $document->descrip; ?></p>
	<strong>Translations</strong>
	<ul>
		<?php foreach ($document->project->languages as $language) :
			if ($language->id != $document->project->default_language_id) : ?>
			<li>
				<a href="document/<?php echo $document->id; ?>/translation/<?php echo $language->id; ?>">
					[<?php echo strtoupper($language->code); ?>]
					<?php echo isset($document->translations[$language->id])
						? $document->translations[$language->id]->title
					 	: "Add Translation for $language->name"; ?>
				</a>
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
