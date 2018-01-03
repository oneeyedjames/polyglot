<form action="project/<?php echo $project->id; ?>/add-user" method="POST">
	<?php $nonce = $this->create_nonce('add-user', 'project'); ?>
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<div class="modal card primary" id="modal-form-user">
		<header>Add User</header>

		<label><i class="fa fa-folder-open"></i> Project</label>
		<em><?php echo $project->title; ?></em>

	    <label class="control-label"><i class="fa fa-user"></i> User</label>
	    <select name="user" class="form-control">
	    	<?php foreach ($users as $user) : if (!isset($project->users[$user->id])) : ?>
	    		<option value="<?php echo $user->id; ?>">
	                <?php echo $user->alias; ?>
	            </option>
	    	<?php endif; endforeach; ?>
	    </select>

	    <label class="control-label"><i class="fa fa-group"></i> Role</label>
		<select name="role" class="form-control">
			<?php foreach ($roles as $role) : ?>
				<option value="<?php echo $role->id; ?>">
					<?php echo $role->title; ?>
				</option>
			<?php endforeach; ?>
		</select>

		<footer>
			<button type="button" class="btn cancel">Cancel</button>
			<button type="submit" class="btn primary">Save</button>
		</footer>
	</div>
</form>
