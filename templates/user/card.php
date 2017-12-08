<div class="card primary">
	<header>
		<a href="user/<?php echo $user->id; ?>"><?php echo $user->name; ?></a>
	</header>
	<strong>Email Address</strong>
	<p><?php echo $user->email; ?></p>
	<div class="row">
		<div class="col-md-6">
			<strong>Projects (<?php echo $user->projects->found; ?>)</strong>
			<ul>
				<?php foreach ($user->projects as $project) : ?>
					<li>
						<a href="project/<?php echo $project->id; ?>">
							<?php echo $project->title; ?>
						</a>
					</li>
				<?php endforeach; ?>
				<?php if ($user->projects->found > count($user->projects)) : ?>
					<li><em>more</em></li>
				<?php endif; ?>
			</ul>
		</div>
		<div class="col-md-6">
			<strong>Languages (<?php echo $user->languages->found; ?>)</strong>
			<ul>
				<?php foreach ($user->languages as $language) : ?>
					<li>
						<a href="language/<?php echo $language->id; ?>">
							<?php echo $language->name; ?>
						</a>
					</li>
				<?php endforeach; ?>
				<?php if ($user->languages->found > count($user->languages)) : ?>
					<li><em>more</em></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<footer>
		<a href="user/<?php echo $user->id; ?>/form-meta" class="btn primary"
			data-action="modal" data-target="#user-form">
			<i class="fa fa-edit"></i> Edit
		</a>
		<a class="btn danger">
			<i class="fa fa-trash"></i> Delete
		</a>
	</footer>
</div>
