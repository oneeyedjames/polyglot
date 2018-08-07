<?php $delete_nonce = $this->create_nonce('delete', 'project'); ?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">Projects</li>
</ol>
<h2 class="page-title">
	<i class="fa fa-folder-open"></i> Projects
</h2>
<div class="btn-toolbar">
	<a href="project/form-meta" target="#modal-card" class="btn success"
		data-action="modal" data-target="#modal-form-meta">
		<i class="fa fa-plus"></i> Add New Project
	</a>
	<div class="pull-right"><?php $this->load('page-limit'); ?></div>
</div>
<table class="primary">
	<thead>
		<tr>
			<th class="snap"></th>
			<th><?php $this->sorting('title', 'Project'); ?></th>
			<th># Languages</th>
			<th># Users</th>
			<th># Documents</th>
			<th># Term Lists</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($projects as $project) : ?>
			<tr>
				<td class="snap">
					<form action="project/<?php echo $project->id; ?>/delete" method="POST" class="btn-group pull-left"
						data-confirm="Are you sure you want to delete this project?">
						<a href="project/<?php echo $project->id; ?>/form-meta" target="#modal-card"
							class="btn primary" title="Edit" data-hover="tooltip"
							data-action="modal" data-target="#modal-form-meta">
							<i class="fa fa-edit"></i>
						</a>
			            <input type="hidden" name="nonce" value="<?php echo $delete_nonce; ?>">
						<button type="submit" class="btn danger" title="Delete" data-hover="tooltip">
			    			<i class="fa fa-trash"></i>
			    		</button>
					</form>
				</td>
				<td><a href="project/<?php echo $project->id; ?>"><?php echo $project->title; ?></a></td>
				<td>
					<?php if ($project->languages->found) :
						$label = ' Language' . ($project->languages->found > 1 ? 's' : ''); ?>
						<a href="project/<?php echo $project->id; ?>/card-languages" target="#modal-card"
							data-action="modal" data-target="#modal-card-languages">
							<?php echo $project->languages->found . $label; ?>
						</a>
					<?php else : ?>
						<em>No Languages</em>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($project->users->found) :
						$label = ' User' . ($project->users->found > 1 ? 's' : ''); ?>
						<a href="project/<?php echo $project->id; ?>/card-users" target="#modal-card"
							data-action="modal" data-target="#modal-card-users">
							<?php echo $project->users->found . $label; ?>
						</a>
					<?php else : ?>
						<em>No Users</em>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($project->documents->found) :
						$label = ' Document' . ($project->documents->found > 1 ? 's' : ''); ?>
						<a href="project/<?php echo $project->id; ?>/card-documents" target="#modal-card"
							data-action="modal" data-target="#modal-card-documents">
							<?php echo $project->documents->found . $label; ?>
						</a>
					<?php else : ?>
						<em>No Documents</em>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($project->lists->found) :
						$label = ' Term List' . ($project->lists->found > 1 ? 's' : ''); ?>
						<a href="project/<?php echo $project->id; ?>/card-lists" target="#modal-card"
							data-action="modal" data-target="#modal-card-lists">
							<?php echo $project->lists->found . $label; ?>
						</a>
					<?php else : ?>
						<em>No Lists</em>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<div class="btn-toolbar">
	<div class="pull-right"><?php $this->pagination($projects->found); ?></div>
</div>
