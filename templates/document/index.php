<?php $delete_nonce = $this->create_nonce('delete', 'document'); ?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li><a href="projects">Projects</a></li>
	<li><a href="project/<?php echo $project->id; ?>"><?php echo $project->title; ?></a></li>
	<li class="active">Documents</li>
</ol>
<h2><i class="fa fa-file"></i> <?php echo $project->title; ?> &raquo; Documents</h2>
<p>
	<a href="project/<?php echo $project->id; ?>/documents/form-meta" target="#modal-card"
		class="btn success" data-action="modal" data-target="#modal-form-meta">
		<i class="fa fa-plus"></i> Add New Document
	</a>
</p>
<p><?php $this->load('page-limit'); ?></p>
<table class="primary">
	<thead>
		<tr>
			<th class="snap"></th>
			<th>Document</th>
			<th>Author</th>
			<th>Created</th>
			<th>Updated</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($documents as $document) : ?>
			<tr>
				<td class="snap">
					<form action="document/<?php echo $document->id; ?>/delete" method="POST" class="btn-group pull-left"
						data-confirm="Are you sure you want to delete this document?">
						<!-- <a href="document/<?php echo $document->id; ?>/form-meta" target="#modal-card"
							class="btn primary" data-action="modal" data-target="#modal-form-meta">
							<i class="fa fa-edit"></i>
						</a> -->
						<a href="document/<?php echo $document->id; ?>/form" class="btn primary">
							<i class="fa fa-edit"></i>
						</a>

			            <input type="hidden" name="nonce" value="<?php echo $delete_nonce; ?>">

						<button type="submit" class="btn danger">
			    			<i class="fa fa-trash"></i>
			    		</button>
					</form>
				</td>
				<td>
					<a href="document/<?php echo $document->id; ?>"><?php echo $document->title; ?></a>
				</td>
				<td><?php echo $document->user->name; ?></td>
				<td><?php echo $document->created; ?></td>
				<td><?php echo $document->updated; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<p><?php $this->pagination($documents->found); ?></p>
<div id="modal-card"></div>
