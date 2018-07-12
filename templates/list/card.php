<?php $delete_nonce = $this->create_nonce('delete', 'list'); ?>
<div class="card primary">
	<header>
		<a href="lists/<?php echo $list->id; ?>"><?php echo $list->title; ?></a>
	</header>
	<p><?php echo $list->descrip; ?></p>
	<p><strong>Translations</strong></p>
	<?php foreach ($list->project->languages as $language) :
		if ($language->id != $list->project->default_language_id) :
			$url = "list/$list->id/translation/$language->id"; ?>
		<p>
			<a href="<?php echo $url; ?>">
				<i class="fa fa-flag"></i> <?php echo $language->name; ?>
			</a>
		</p>
	<?php endif; endforeach; ?>
	<footer class="align-right">
		<form action="list/<?php echo $list->id; ?>/delete" method="POST"
			data-confirm="Are you sure you want to delete this list?">
			<input type="hidden" name="nonce" value="<?php echo $delete_nonce; ?>">
			<a href="lists/<?php echo $list->id; ?>/form-meta" target="#modal-card"
				class="btn primary" data-action="modal" data-target="#modal-form-meta">
				<i class="fa fa-edit"></i> Edit
			</a>
			<button type="submit" class="btn danger">
				<i class="fa fa-trash"></i> Delete
			</button>
		</form>
	</footer>
</div>
