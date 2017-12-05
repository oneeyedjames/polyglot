<?php

$master = $term->master ?: $term;

if (isset($term->id))
	$url = "term/$term->id/save";
elseif (isset($term->list))
	$url = "list/{$term->list->id}/terms/save";

$nonce = $this->create_nonce('save', 'term');

?>
<form action="<?php echo $url; ?>" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<header><i class="fa fa-terminal"></i> Edit Term</header>

	<?php if ($term->language) : ?>
		<label>Language</label>
		<input type="hidden" name="term[language]" value="<?php echo $term->language->id; ?>">
		<input type="text" value="<?php echo $term->language->name; ?>" disabled="true">
	<?php endif; ?>

	<label>Title</label>
	<?php if ($term->master) : ?>
		<div class="card alert"><?php echo $term->master->content; ?></div>
	<?php endif; ?>
	<input id="term-content" type="text" name="term[content]" value="<?php echo $term->content; ?>">

	<?php if ($term->master) : ?>
		<input type="hidden" name="term[master]" value="<?php echo $term->master->id; ?>">
	<?php endif; ?>

	<label>Description</label>
	<?php if ($term->master) : ?>
		<div class="card alert" style="white-space: pre-wrap;"><?php echo $term->master->descrip; ?></div>
	<?php endif; ?>
	<textarea id="term-description" name="term[description]" rows="2"><?php echo $term->descrip; ?></textarea>

	<footer>
		<button type="button" class="btn cancel">Cancel</button>
		<button type="submit" class="btn primary">Save</button>
	</footer>
</form>
