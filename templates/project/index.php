<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">Projects</li>
</ol>
<h2><i class="fa fa-folder-open"></i> Projects</h2>
<p>
	<a class="btn green" data-action="modal" data-target="#project-form" href="project/form-meta">
		<i class="fa fa-plus"></i> Add New Project
	</a>
</p>
<div class="row">
	<?php foreach ($projects as $project) : ?>
		<div class="col-md-6 col-lg-4">
			<?php $this->load('card', 'project', compact('project')); ?>
		</div>
	<?php endforeach; ?>
</div>
<div class="card modal col-md-8 col-lg-6 blue" id="project-form"></div>
