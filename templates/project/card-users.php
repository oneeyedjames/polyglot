<div class="card cyan">
	<header>
		<i class="fa fa-user"></i> Users
		<a class="pull-right"
			href="project/<?php echo $project->id; ?>/form-user"
			data-action="modal" data-target="#modal-form-user"
			data-parent="#modal-form">
			<i class="fa fa-plus"></i> Add
		</a>
	</header>
	<ul>
		<?php foreach ($project->users as $user) : ?>
			<li>
				<strong><?php echo $user->name;?></strong>
				<a class="txt-btn red pull-right"
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
