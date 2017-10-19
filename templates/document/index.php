<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li><a href="projects">Projects</a></li>
	<li><a href="project/<?php echo $project->id; ?>"><?php echo $project->title; ?></a></li>
	<li class="active">Documents</li>
</ol>
<h2><i class="fa fa-file"></i> <?php echo $project->title; ?> &raquo; Documents</h2>
<p>
	<a href="project/<?php echo $project->id; ?>/documents/form" class="btn green" data-action="modal" data-target="#document-form">
		<i class="fa fa-plus"></i> Add New Document
	</a>
</p>
<p><?php $this->load('page-limit'); ?></p>
<div class="row">
	<?php foreach ($documents as $document) : $document->project = $project; ?>
		<div class="col-md-6 col-lg-4">
			<?php $this->load('card', 'document', compact('document')); ?>
		</div>
	<?php endforeach; ?>
</div>
<p><?php $this->pagination($documents->found); ?></p>
<div class="card modal blue" id="list-form"></div>
