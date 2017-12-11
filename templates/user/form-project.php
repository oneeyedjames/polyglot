<?php $nonce = $this->create_nonce('add-project', 'user'); ?>
<form action="user/<?php echo $user->id; ?>/add-project" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<div class="modal card primary" id="modal-form-project">
		<header><i class="fa fa-user"></i> <?php echo $user->name; ?></header>

		<label><i class="fa fa-folder-open"></i> Project</label>
		<select name="project">
			<?php foreach ($projects as $project) : ?>
				<option value="<?php echo $project->id; ?>"><?php echo $project->title; ?></option>
			<?php endforeach; ?>
		</select>

		<label><i class="fa fa-users"></i> Role</label>
		<select name="role">
			<?php foreach ($roles as $role) : ?>
				<option value="<?php echo $role->id; ?>"><?php echo $role->title; ?></option>
			<?php endforeach; ?>
		</select>

		<footer>
			<button type="button" class="btn cancel">Cancel</button>
			<button type="submit" class="btn primary">Save</button>
		</footer>
	</div>
</form>
