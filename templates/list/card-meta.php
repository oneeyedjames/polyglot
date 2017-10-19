<div class="card">
	<header><i class="fa fa-info-circle"></i> Term List</header>

	<strong>Project</strong>
	<p><a href="projects/<?php echo $list->project->id; ?>"><?php echo $list->project->title; ?></a></p>

	<strong>Language</strong>
	<p><?php echo $list->language->name; ?></p>

	<strong>Author</strong>
	<p><?php echo $list->user->name; ?></p>

	<strong>Created</strong>
	<p><?php echo $list->created; ?></p>

	<footer>
		<a class="btn blue" href="lists/<?php echo $list->id; ?>/form">
			<i class="fa fa-edit"></i> Edit
		</a>
		<a class="btn red">
			<i class="fa fa-trash"></i> Delete
		</a>
	</footer>
</div>
