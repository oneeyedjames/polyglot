<?php $delete_nonce = $this->create_nonce('delete', 'document'); ?>
<div class="card primary">
	<header>
		<a href="document/<?php echo $document->id; ?>"><?php echo $document->title; ?></a>
	</header>
	<p><?php echo $document->descrip; ?></p>
	<p><strong>Translations</strong></p>
	<?php foreach ($document->project->languages as $language) :
		if ($language->id != $document->project->default_language_id) :
			$url = "document/$document->id/translation/$language->id"; ?>
		<p>
			<?php if (isset($document->translations[$language->id])) :
				$translation = $document->translations[$language->id]; ?>
				<a href="<?php echo $url; ?>"><?php echo $translation->title; ?></a>
			<?php else : ?>
				<a href="<?php echo $url; ?>" class="btn sm text success">
					<i class="fa fa-plus"></i> Add Translation
				</a>
			<?php endif; ?>
			<br>
			<i class="fa fa-flag"></i> <?php echo $language->name; ?>
		</p>
	<?php endif; endforeach; ?>
	<footer class="align-right">
		<form action="document/<?php echo $document->id; ?>/delete" method="POST"
			data-confirm="Are you sure you want to delete this document?">
			<input type="hidden" name="nonce" value="<?php echo $delete_nonce; ?>">
			<a href="document/<?php echo $document->id; ?>/form" class="btn primary">
				<i class="fa fa-edit"></i> Edit
			</a>
			<button type="submit" class="btn danger">
				<i class="fa fa-trash"></i> Delete
			</button>
		</form>
	</footer>
</div>
