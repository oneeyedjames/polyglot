<?php $master = $document->master ?: $document; ?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li><a href="projects">Projects</a></li>
	<li><a href="project/<?php echo $document->project->id; ?>"><?php echo $document->project->title; ?></a></li>
	<li><a href="project/<?php echo $document->project->id; ?>/documents">Documents</a></li>
	<li class="active"><?php echo $master->title; ?></li>
</ol>
<div class="row">
	<div class="col-sm-12 col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
		<h2>
			<i class="fa fa-file"></i>
			<?php echo $document->title; ?>
			<?php if ($document->revision) : ?>
				<span class="small">
					<i class="fa fa-chevron-left"></i>
					Revision of
					<a href="document/<?php echo $master->id; ?>">
						<?php echo $master->title; ?>
					</a>
				</span>
			<?php elseif ($document->master) : ?>
				<span class="small">
					<i class="fa fa-chevron-left"></i>
					Translation of
					<a href="document/<?php echo $master->id; ?>">
						<?php echo $master->title; ?>
					</a>
				</span>
			<?php endif; ?>
		</h2>
		<p class="lead"><?php echo $document->descrip; ?></p>
		<p>
			<a href="document/<?php echo $document->id; ?>/form" title="Edit" class="btn primary">
				<i class="fa fa-edit"></i> Edit
			</a>
			<a class="btn danger">
				<i class="fa fa-trash"></i> Delete
			</a>
		</p>
		<p style="white-space: pre-wrap"><?php echo $document->content; ?></p>
	</div>
	<div class="col-sm-12 col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9">
		<?php
			$this->load('item-meta', 'document', compact('document'));
			$this->load('item-translation', 'document', compact('document'));
			$this->load('item-revision', 'document', compact('document'));
		?>
	</div>
</div>
