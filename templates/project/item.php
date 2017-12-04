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
					<a href="project/<?php echo $project->id; ?>/documents/form-meta"
						class="btn green" data-action="modal"
						data-target="#modal-form-document"
						data-parent="#modal-form">
						<i class="fa fa-plus"></i> Add New Document
					</a>
				</p>
				<?php foreach ($project->documents as $document) {
					$document->project = $project;
					$this->load('card', 'document', compact('document'));
				} ?>
				<a class="btn" href="project/<?php echo $project->id; ?>/documents">
					See More <i class="fa fa-chevron-right"></i>
				</a>
			</div>
			<div class="col-sm-12 col-md-6">
				<h3><i class="fa fa-list"></i> Term Lists</h3>
				<p>
					<a href="project/<?php echo $project->id; ?>/lists/form-meta"
						class="btn green" data-action="modal"
						data-target="#modal-form-list"
						data-parent="#modal-form">
						<i class="fa fa-plus"></i> Add New Term List
					</a>
				</p>
				<?php foreach ($project->lists as $list) {
					$list->project = $project;
					$this->load('card', 'list', compact('list'));
				} ?>
				<a class="btn" href="project/<?php echo $project->id; ?>/lists">
					See More <i class="fa fa-chevron-right"></i>
				</a>
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
<div id="modal-form"></div>
