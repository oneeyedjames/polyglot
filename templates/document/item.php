<?php

$master = $document->master ?: $document;

$delete_nonce = $this->create_nonce('delete', 'document');

?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li><a href="projects">Projects</a></li>
	<li><a href="project/<?php echo $document->project->id; ?>"><?php echo $document->project->title; ?></a></li>
	<li><a href="project/<?php echo $document->project->id; ?>/documents">Documents</a></li>
	<li class="active"><?php echo $master->title; ?></li>
</ol>
<div class="row">
	<div class="col-sm-12 col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
		<h2 class="page-title">
			<i class="fa fa-file"></i> <?php echo $document->title; ?>
			<form action="document/<?php echo $document->id; ?>/delete" method="POST" class="btn-group pull-right"
				data-confirm="Are you sure you want to delete this term list?">
				<a href="document/<?php echo $document->id; ?>/form" class="btn primary">
					<i class="fa fa-edit"></i> Edit
				</a>
				<input type="hidden" name="nonce" value="<?php echo $delete_nonce; ?>">
				<button type="submit" class="btn danger">
					<i class="fa fa-trash"></i> Delete
				</button>
			</form>
			<?php if ($document->revision) : ?>
				<div class="small">
					<i class="fa fa-clock-o"></i> Revision of
					<a href="document/<?php echo $master->id; ?>">
						<?php echo $master->title; ?>
					</a>
				</div>
			<?php elseif ($document->master) : ?>
				<div class="small">
					<i class="fa fa-flag"></i> Translation of
					<a href="document/<?php echo $master->id; ?>">
						<?php echo $master->title; ?>
					</a>
				</div>
			<?php endif; ?>
		</h2>
		<p class="lead"><?php echo $document->descrip; ?></p>
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
