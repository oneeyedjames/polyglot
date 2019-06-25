<?php $delete_nonce = $this->create_nonce('delete', 'list'); ?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li><a href="projects">Projects</a></li>
	<li><a href="project/<?php echo $project->id; ?>"><?php echo $project->title; ?></a></li>
	<li class="active">Term Lists</li>
</ol>
<h2 class="page-title">
	<i class="fa fa-list"></i> Term Lists
	<div class="small">
		<i class="fa fa-sitemap"></i> In Project
		<a href="project/<?php echo $project->id; ?>">
			<?php echo $project->title; ?>
		</a>
	</div>
</h2>
<div class="btn-toolbar">
	<a href="project/<?php echo $project->id; ?>/lists/form-meta" target="#modal-card"
		class="btn success" data-action="modal" data-target="#modal-form-meta">
		<i class="fa fa-plus"></i> Add New Term List
	</a>
	<div class="pull-right"><?php $this->load('page-limit'); ?></div>
</div>
<table class="table striped primary">
	<thead>
		<tr>
			<th class="snap"></th>
			<th>Term List</th>
			<th>Author</th>
			<th>Created</th>
			<th>Updated</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($lists as $list) : ?>
			<tr>
				<td class="snap">
					<form action="list/<?php echo $list->id; ?>/delete" method="POST" class="btn-group pull-left"
						data-confirm="Are you sure you want to delete this term list?">
						<a href="lists/<?php echo $list->id; ?>/form-meta" target="#modal-card"
							class="btn primary" data-action="modal" data-target="#modal-form-meta">
							<i class="fa fa-edit"></i>
						</a>
						<input type="hidden" name="nonce" value="<?php echo $delete_nonce; ?>">
						<button type="submit" class="btn danger">
							<i class="fa fa-trash"></i>
						</button>
					</form>
				</td>
				<td>
					<a href="lists/<?php echo $list->id; ?>">
						<?php echo $list->title; ?>
					</a>
				</td>
				<td><?php echo $list->user->alias; ?></td>
				<td><?php echo $list->created; ?></td>
				<td><?php echo $list->updated; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<div class="btn-toolbar">
	<div class="pull-right"><?php $this->pagination($lists->found); ?></div>
</div>
