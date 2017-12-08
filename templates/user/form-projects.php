<?php $nonce = $this->create_nonce('save', 'user'); ?>
<form action="user/<?php echo $user->id; ?>/save" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<div class="modal card primary" id="modal-form-projects">
		<header><i class="fa fa-user"></i> <?php echo $user->name; ?></header>

		<strong><i class="fa fa-folder-open"></i> Projects</strong>

		<?php foreach ($projects as $project) :
			$role_id = isset($user->projects[$project->id]) ? $user->projects[$project->id]->role->id : 0; ?>
			<label>
				<?php echo $project->title; ?>
				<select name="projects[<?php echo $project->id; ?>]">
					<option value="0">- Unassigned -</option>
					<?php foreach ($roles as $role) : ?>
						<option value="<?php echo $role->id; ?>"<?php selected($role_id, $role->id); ?>><?php echo $role->title; ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		<?php endforeach; ?>

		<footer style="text-align: right;">
			<button type="button" class="btn cancel">Cancel</button>
			<button type="submit" class="btn primary">Save</button>
		</footer>
	</div>
</form>
