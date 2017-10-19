<?php

$master = $document->master ?: $document;

if (isset($document->id))
	$url = "document/$document->id/save";
elseif (isset($document->project))
	$url = "project/{$document->project->id}/documents/save";

$nonce = $this->create_nonce('save', 'document');

?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li><a href="projects">Projects</a></li>
	<li><a href="project/<?php echo $document->project->id; ?>"><?php echo $document->project->title; ?></a></li>
	<li><a href="project/<?php echo $document->project->id; ?>/documents">Documents</a></li>
	<li class="active"><?php echo $master->title; ?></li>
</ol>
<h2><i class="fa fa-edit"></i> Edit Document</h2>
<form action="<?php echo $url; ?>" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">

	<?php if ($document->master) : ?>
		<input type="hidden" name="document[master]" value="<?php echo $master->id; ?>">
	<?php endif; ?>

	<label>Language</label>
	<input type="text" value="<?php echo $document->language->name; ?>" readonly="readonly">
	<input type="hidden" name="document[language]" value="<?php echo $document->language_id; ?>">

	<label>Title</label>
	<?php if ($document->master) : ?>
		<div class="row">
			<div class="col-md-6">
				<div class="card alert yellow"><?php echo $master->title; ?></div>
			</div>
			<div class="col-md-6">
				<input type="text" name="document[title]" value="<?php echo $document->title; ?>">
			</div>
		</div>
	<?php else : ?>
		<input type="text" name="document[title]" value="<?php echo $document->title; ?>">
	<?php endif; ?>

	<label>Description</label>
	<?php if ($document->master) : ?>
		<div class="row">
			<div class="col-md-6">
				<div class="card alert yellow"><?php echo $master->descrip; ?></div>
			</div>
			<div class="col-md-6">
				<textarea name="document[description]" rows="4"><?php echo $document->descrip; ?></textarea>
			</div>
		</div>
	<?php else : ?>
		<textarea name="document[description]" rows="4"><?php echo $document->descrip; ?></textarea>
	<?php endif; ?>

	<label>Content</label>
	<?php if ($document->master) : ?>
		<div class="row">
			<div class="col-md-6">
				<div class="card alert yellow" style="white-space: pre-wrap;"><?php echo $master->content; ?></div>
			</div>
			<div class="col-md-6">
				<textarea name="document[content]" rows="24"><?php echo $document->content; ?></textarea>
			</div>
		</div>
	<?php else : ?>
		<textarea name="document[content]" rows="24"><?php echo $document->content; ?></textarea>
	<?php endif; ?>

	<footer>
		<button type="button" class="btn cancel">Cancel</button>
		<button type="submit" class="btn blue">Save</button>
	</footer>
</form>
