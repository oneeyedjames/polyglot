<?php

if (isset($document->id))
	$url = "document/$document->id/save";
elseif (isset($document->project))
	$url = "project/{$document->project->id}/documents/save";

$nonce = $this->create_nonce('save', 'document');

?>
<form action="<?php echo $url; ?>" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<div class="modal card primary" id="modal-form-meta">
		<header><i class="fa fa-file"></i> Edit Document</header>

		<label>Title</label>
		<input type="text" name="document[title]" value="<?php echo $document->title; ?>">

		<?php if (isset($document->language_id)) : ?>
			<label>Language</label>
			<input type="hidden" name="document[language]" value="<?php echo $document->language_id; ?>">
			<input type="text" value="<?php echo $document->language->name; ?>" disabled="true">
		<?php endif; ?>

		<label>Description</label>
		<textarea name="document[description]" rows="2"><?php echo $document->descrip; ?></textarea>

		<footer>
			<button type="button" class="btn cancel">Cancel</button>
			<button type="submit" class="btn primary">Save</button>
		</footer>
	</div>
</form>
