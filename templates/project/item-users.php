<div class="card info">
	<header>
		<i class="fa fa-user"></i> Users
		<a href="project/<?php echo $project->id; ?>/form-user" target="#modal-card"
			class="pull-right" data-action="modal" data-target="#modal-form-user">
			<i class="fa fa-plus"></i> Add
		</a>
	</header>
	<ul>
		<?php foreach ($project->users as $user) : ?>
			<li>
				<a href="user/<?php echo $user->id; ?>">
					<strong><?php echo $user->name;?></strong>
				</a>
				<a class="btn sm text danger pull-right"
					data-action="submit" data-target="#remove-user-form"
					data-input-user="<?php echo $user->id; ?>">
					<i class="fa fa-minus"></i> Remove
				</a>
				<div><?php echo $user->role->title; ?></div>
			</li>
		<?php endforeach; ?>
	</ul>
    <form id="remove-user-form" action="project/<?php echo $project->id; ?>/remove-user" method="POST"
    	data-confirm="Are you sure you want to remove this user from the project?">
    	<?php $nonce = $this->create_nonce('remove-user', 'project'); ?>
    	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
    	<input type="hidden" name="user">
    </form>
</div>
