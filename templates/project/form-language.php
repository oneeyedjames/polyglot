<?php $nonce = $this->create_nonce('add-language', 'project'); ?>
<form action="project/<?php echo $project->id; ?>/add-language" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<header>Add Language</header>

	<label><i class="fa fa-folder-open"></i> Project</label>
	<em><?php echo $project->title; ?></em>

	<label><i class="fa fa-flag"></i> Language</label>
	<select name="language" class="checkbox">
		<?php foreach ($languages as $language) :
			if (!isset($project->languages[$language->id])) : ?>
			<option value="<?php echo $language->id; ?>"><?php echo $language->name; ?></option>
		<?php endif; endforeach; ?>
	</select>

	<footer style="text-align: right;">
		<button type="button" class="btn cancel">Cancel</button>
		<button type="submit" class="btn blue">Save</button>
	</footer>
</form>
