<div class="card blue">
	<header>
		<a href="project/<?php echo $project->id; ?>"><?php echo $project->title; ?></a>
	</header>
	<p><?php echo $project->descrip; ?></p>
	<div class="row">
		<div class="col-md-6">
			<strong>Languages (<?php echo $project->languages->found; ?>)</strong>
			<ul>
				<?php foreach ($project->languages as $language) : ?>
					<li>
						<a href="language/<?php echo $language->id; ?>">
							<?php echo $language->name; ?>
						</a>
					</li>
				<?php endforeach; ?>
				<?php if ($project->languages->found > count($project->languages)) : ?>
					<li><em>more</em></li>
				<?php endif; ?>
			</ul>
		</div>
		<div class="col-md-6">
			<strong>Users (<?php echo $project->users->found; ?>)</strong>
			<ul>
				<?php foreach ($project->users as $user) : ?>
					<li>
						<a href="user/<?php echo $user->id; ?>">
							<?php echo $user->name; ?>
						</a>
					</li>
				<?php endforeach; ?>
				<?php if ($project->users->found > count($project->users)) : ?>
					<li><em>more</em></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<footer>
		<a href="project/<?php echo $project->id; ?>/form-meta" class="btn blue"
			data-action="modal" data-target="#project-form">
			<i class="fa fa-edit"></i> Edit
		</a>
		<a class="btn red">
			<i class="fa fa-trash"></i> Delete
		</a>
	</footer>
</div>
