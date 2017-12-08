<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li><a href="projects">Projects</a></li>
	<li class="active"><?php echo $project->title; ?></li>
</ol>
<div class="row">
	<div class="col-sm-12 col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
		<h2><i class="fa fa-folder-open"></i> <?php echo $project->title; ?></h2>
		<p class="lead"><?php echo $project->descrip; ?></p>
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<h3><i class="fa fa-file"></i> Documents</h3>
				<p>
					<a href="project/<?php echo $project->id; ?>/documents/form-meta" target="#modal-card"
						class="btn success" data-action="modal" data-target="#modal-form-meta">
						<i class="fa fa-plus"></i> Add New Document
					</a>
					<a href="project/<?php echo $project->id; ?>/documents" class="btn">
						See More <i class="fa fa-chevron-right"></i>
					</a>
				</p>
				<?php foreach ($project->documents as $document) {
					$document->project = $project;
					$this->load('card', 'document', compact('document'));
				} ?>
			</div>
			<div class="col-sm-12 col-md-6">
				<h3><i class="fa fa-list"></i> Term Lists</h3>
				<p>
					<a href="project/<?php echo $project->id; ?>/lists/form-meta" target="#modal-card"
						class="btn success" data-action="modal" data-target="#modal-form-meta">
						<i class="fa fa-plus"></i> Add New Term List
					</a>
					<a href="project/<?php echo $project->id; ?>/lists" class="btn">
						See More <i class="fa fa-chevron-right"></i>
					</a>
				</p>
				<?php foreach ($project->lists as $list) {
					$list->project = $project;
					$this->load('card', 'list', compact('list'));
				} ?>
			</div>
		</div>
	</div>
	<div class="col-sm-12 col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9">
		<?php
			$this->load('card-languages', 'project', compact('project'));
			$this->load('card-users', 'project', compact('project'));
		?>
	</div>
</div>
<div id="modal-card"></div>
