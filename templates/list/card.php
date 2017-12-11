<div class="card primary">
	<header>
		<a href="lists/<?php echo $list->id; ?>"><?php echo $list->title; ?></a>
	</header>
	<p><?php echo $list->descrip; ?></p>
	<strong>Translations</strong>
	<ul>
		<?php foreach ($list->project->languages as $language) :
			if ($language->id != $list->project->default_language_id) : ?>
			<li>
				<a href="list/<?php echo $list->id; ?>/translation/<?php echo $language->id; ?>">
					<?php echo $language->name; ?>
				</a>
			</li>
		<?php endif; endforeach; ?>
	</ul>
	<footer>
		<a href="lists/<?php echo $list->id; ?>/form-meta" target="#modal-card"
			class="btn primary" data-action="modal" data-target="#modal-form-meta">
			<i class="fa fa-edit"></i> Edit
		</a>
		<a class="btn danger">
			<i class="fa fa-trash"></i> Delete
		</a>
	</footer>
</div>
