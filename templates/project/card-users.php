<div class="modal card info" id="modal-card-users">
	<header>
		<i class="fa fa-folder-open"></i> <?php echo $project->title; ?>
		<a href="#" class="cancel pull-right"><i class="fa fa-close"></i></a>
	</header>
	<strong><i class="fa fa-user"></i> Users</strong>
	<ul>
		<?php foreach ($project->users as $user) : ?>
			<li>
				<a href="user/<?php echo $user->id; ?>">
					<?php echo $user->alias; ?>
				</a>
			</li>
		<?php endforeach; ?>
		<?php if ($project->users->found > count($project->users)) : ?>
			<li><em>more</em></li>
		<?php endif; ?>
	</ul>
</div>
