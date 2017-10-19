<?php $nonce = $this->create_nonce('save', 'project'); ?>
<form action="project/<?php echo $project->id; ?>/save" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<header>Edit Project</header>

	<h3><i class="fa fa-folder-open"></i> Project</h3>
	<strong><?php echo $project->title; ?></strong>

	<h3><i class="fa fa-flag"></i> Languages</h3>
	<div class="checkbox">
		<?php foreach ($languages as $language) : ?>
			<label>
				<input type="checkbox" name="languages[]" value="<?php echo $language->id; ?>"<?php checked(isset($project->languages[$language->id])); ?>>
				<?php echo $language->name; ?>
			</label>
		<?php endforeach; ?>
	</div>

	<footer style="text-align: right;">
		<button type="button" class="btn cancel">Cancel</button>
		<button type="submit" class="btn blue">Save</button>
	</footer>
</form>
