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
<div class="row">
	<div class="col-sm-12 col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
		<h2 class="page-title">
			<i class="fa fa-edit"></i> Edit Document
			<div class="btn-group pull-right">
				<a class="btn primary" data-action="submit" data-target="#edit-document-form">
					<i class="fa fa-save"></i> Save
				</a>
				<a href="document/<?php echo $document->id; ?>" class="btn">
					<i class="fa fa-close"></i> Cancel
				</a>
			</div>
		</h2>
		<form action="<?php echo $url; ?>" method="POST" id="edit-document-form">
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
						<div class="card alert"><?php echo $master->title; ?></div>
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
						<div class="card alert"><?php echo $master->descrip; ?></div>
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
						<div class="card alert" style="white-space: pre-wrap;"><?php echo $master->content; ?></div>
					</div>
					<div class="col-md-6">
						<textarea name="document[content]" id="document-content"><?php echo $document->content; ?></textarea>
					</div>
				</div>
			<?php else : ?>
				<textarea name="document[content]" id="document-content"><?php echo $document->content; ?></textarea>
			<?php endif; ?>
		</form>
	</div>
	<div class="col-sm-12 col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9">
		<?php $this->load('item-meta', 'document', compact('document')); ?>
	</div>
</div>
<script type="text/javascript">
	tinymce.init({
		selector: '#document-content',
		toolbar: 'undo redo | styleselect | bold italic underline strikethrough | subscript superscript | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | blockquote link | removeformat',
		menubar: false,
		plugins: 'lists, link'
	});
</script>
