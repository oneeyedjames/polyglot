<?php $nonce = $this->create_nonce('add-user', 'project'); ?>
<form action="project/<?php echo $project->id; ?>/add-user" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<header>Edit Project</header>

	<label><i class="fa fa-folder-open"></i> Project</label>
	<em><?php echo $project->title; ?></em>

    <label class="control-label"><i class="fa fa-user"></i> User</label>
    <select name="user" class="form-control">
    	<?php foreach ($users as $user) : if (!isset($project->users[$user->id])) : ?>
    		<option value="<?php echo $user->id; ?>">
                <?php echo $user->name; ?>
            </option>
    	<?php endif; endforeach; ?>
    </select>

    <label class="control-label"><i class="fa fa-group"></i> Role</label>
    <!-- <div class="radio"> -->
	<select name="role" class="form-control">
		<?php foreach ($roles as $role) : ?>
			<option value="<?php echo $role->id; ?>">
				<?php echo $role->title; ?>
			</option>
			<!-- <label>
				<input type="radio" name="role" value="<?php echo $role->id; ?>">
				<span><?php echo $role->title; ?></span>
			</label> -->
		<?php endforeach; ?>
	</select>
	<!-- </div> -->

	<footer style="text-align: right;">
		<button type="button" class="btn cancel">Cancel</button>
		<button type="submit" class="btn blue">Save</button>
	</footer>
</form>
