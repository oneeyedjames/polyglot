<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li><a href="projects">Projects</a></li>
	<li><a href="project/<?php echo $project->id; ?>"><?php echo $project->title; ?></a></li>
	<li class="active">Term Lists</li>
</ol>
<h2><i class="fa fa-list"></i> <?php echo $project->title; ?> &raquo; Term Lists</h2>
<p>
	<a href="project/<?php echo $project->id; ?>/lists/form-meta" class="btn success" data-action="modal" data-target="#list-form">
		<i class="fa fa-plus"></i> Add New Term List
	</a>
</p>
<p><?php $this->load('page-limit'); ?></p>
<div class="row">
	<?php foreach ($lists as $list) : $list->project = $project; ?>
		<div class="col-md-6 col-lg-4">
			<?php $this->load('card', 'list', compact('list')); ?>
		</div>
	<?php endforeach; ?>
</div>
<p><?php $this->pagination($lists->found); ?></p>
<div class="modal card primary" id="list-form"></div>
