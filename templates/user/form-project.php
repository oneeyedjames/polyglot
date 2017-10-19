<?php $nonce = $this->create_nonce('save', 'user'); ?>
<form action="user/<?php echo $user->id; ?>/save" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<header>Edit User</header>

	<h3><i class="fa fa-user"></i> User</h3>
	<strong><?php echo $user->name; ?></strong>

	<h3><i class="fa fa-folder-open"></i> Projects</h3>
	<?php foreach ($projects as $project) :
		$role_id = isset($user->projects[$project->id]) ? $user->projects[$project->id]->role->id : 0; ?>
		<label>
			<?php echo $project->title; ?>
			<select name="projects[<?php echo $project->id; ?>]">
				<option value="0">- Unassigned -</option>
				<?php foreach ($roles as $role) : ?>
					<option value="<?php echo $role->id; ?>"<?php selected($role_id, $role->id); ?>><?php echo $role->name; ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	<?php endforeach; ?>

	<footer style="text-align: right;">
		<button type="button" class="btn cancel">Cancel</button>
		<button type="submit" class="btn blue">Save</button>
	</footer>
</form>
