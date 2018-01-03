<div class="card info">
	<header><i class="fa fa-info-circle"></i> Term List</header>

	<strong><i class="fa fa-folder-open"></i> Project</strong>
	<p><a href="projects/<?php echo $list->project->id; ?>"><?php echo $list->project->title; ?></a></p>

	<strong><i class="fa fa-flag"></i> Language</strong>
	<p><?php echo $list->language->name; ?></p>

	<strong><i class="fa fa-user"></i> Author</strong>
	<p><?php echo $list->user->alias; ?></p>

	<strong><i class="fa fa-calendar"></i> Created</strong>
	<p><?php echo $list->created; ?></p>

	<strong><i class="fa fa-calendar"></i> Updated</strong>
	<p><?php echo $list->updated; ?></p>
</div>
