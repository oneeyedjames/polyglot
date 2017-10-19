<?php $nonce = $this->create_nonce('add-user', 'project'); ?>
<form action="project/<?php echo $project->id; ?>/add-user" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<header>Edit Project</header>

	<h3><i class="fa fa-folder-open"></i> Project</h3>
	<strong><?php echo $project->title; ?></strong>

	<h3><i class="fa fa-user"></i> Add User</h3>
    <label class="control-label">User</label>
    <select name="user" class="form-control">
    	<?php foreach ($users as $user) : if (!isset($project->users[$user->id])) : ?>
    		<option value="<?php echo $user->id; ?>">
                <?php echo $user->name; ?>
            </option>
    	<?php endif; endforeach; ?>
    </select>
    <label class="control-label">Role</label>
    <div class="radio">
		<?php foreach ($roles as $role) : ?>
			<label>
				<input type="radio" name="role" value="<?php echo $role->id; ?>">
				<span><?php echo $role->name; ?></span>
			</label>
		<?php endforeach; ?>
	</div>

	<footer style="text-align: right;">
		<button type="button" class="btn cancel">Cancel</button>
		<button type="submit" class="btn blue">Save</button>
	</footer>
</form>
