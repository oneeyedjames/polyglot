<?php

$doc_id  = intval($_GET['id']);
$lang_id = intval($_GET['filter']['language']);

$document = $this->get_document($doc_id);
$language = $this->get_language($lang_id);

$translation = $this->get_translation($doc_id, $lang_id);

if ($translation) {
	$action_url = "documents/$translation->id/save";
} else {
	$action_url = "documents/save";

	$translation = (object) array(
		'title'   => '',
		'summary' => '',
		'content' => ''
	);
}

$user = get_session_user();

?><ol class="breadcrumb">
	<li><a href="home">Home</a></li>
	<li><a href="projects">Projects</a></li>
	<li class="active"><?php echo $document->title; ?></li>
</ol>
<h3><?php echo $document->title; ?></h3>
<form action="<?php echo $action_url; ?>" method="POST">
	<div class="btn-toolbar">
		<input type="hidden" name="token" value="<?php echo $user->create_action_token('save', 'document'); ?>">
		<input type="hidden" name="document[master_id]" value="<?php echo $document->id; ?>">
		<input type="hidden" name="document[language_id]" value="<?php echo $language->id; ?>">
		<input type="hidden" name="document[project_id]" value="<?php echo $document->project_id; ?>">
		<button class="btn btn-primary">Save</button>
	</div>
	<div class="row">
		<div class="col-md-6">
			<h4><?php echo $document->language->name; ?></h4>
			<div class="form-group">
				<label class="control-label">Title</label>
				<input type="text" class="form-control" value="<?php echo $document->title; ?>" readonly>
			</div>
			<div class="form-group">
				<label class="control-label">Summary</label>
				<textarea class="form-control" rows="4" readonly><?php echo $document->summary; ?></textarea>
			</div>
			<div class="form-group">
				<label class="control-label">Content</label>
				<textarea class="form-control" rows="16" readonly><?php echo $document->content; ?></textarea>
			</div>
		</div>
		<div class="col-md-6">
			<h4><?php echo $language->name; ?></h4>
			<div class="form-group">
				<label class="control-label">Title</label>
				<input type="text" name="document[title]" class="form-control" value="<?php echo $translation->title; ?>">
			</div>
			<div class="form-group">
				<label class="control-label">Summary</label>
				<textarea name="document[summary]" class="form-control" rows="4"><?php echo $translation->summary; ?></textarea>
			</div>
			<div class="form-group">
				<label class="control-label">Content</label>
				<textarea name="document[content]" class="form-control" rows="16"><?php echo $translation->content; ?></textarea>
			</div>
		</div>
	</div>
</form>
