<?php

if (isset($project->id))
	$url = "project/$project->id";
else
	$url = 'projects';

$url .= '/save';

$nonce = $this->create_nonce('save', 'project');

?>
<form action="<?php echo $url; ?>" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<div class="modal card primary" id="modal-form-meta">
		<header>
			<i class="fa fa-folder-open"></i> Edit Project
			<a class="pull-right cancel"><i class="fa fa-close"></i></a>
		</header>

		<label class="control-label">Title</label>
		<input type="text" name="project[title]" value="<?php echo $project->title; ?>" class="form-control">

		<label class="control-label">Description</label>
		<textarea name="project[description]" class="form-control"><?php echo $project->descrip; ?></textarea>

		<label class="control-label">Default Language</label>
		<select name="project[language]" class="form-control">
			<?php foreach ($languages as $language) : ?>
				<option value="<?php echo $language->id; ?>"<?php echo $language->id == $project->default_language_id ? ' selected="selected"' : ''; ?>><?php echo $language->name; ?></option>
			<?php endforeach; ?>
		</select>

		<footer class="btns">
			<button type="button" class="btn cancel">Cancel</button>
			<button type="submit" class="btn primary">Save</button>
		</footer>
	</div>
</form>
