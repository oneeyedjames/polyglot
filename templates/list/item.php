<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li><a href="projects">Projects</a></li>
	<li><a href="project/<?php echo $list->project->id; ?>"><?php echo $list->project->title; ?></a></li>
	<li><a href="project/<?php echo $list->project->id; ?>/lists">Term Lists</a></li>
	<li class="active"><?php echo $list->title; ?></li>
</ol>
<div class="row">
	<div class="col-sm-12 col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
		<h2>
			<i class="fa fa-list"></i>
			<?php echo $list->title; ?>
		</h2>
		<p class="lead"><?php echo $list->descrip; ?></p>
		<p>
			<a href="list/<?php echo $list->id; ?>/terms/form-meta" class="btn success" data-target="#term-form" data-action="modal">
				<i class="fa fa-plus"></i> Add New Term
			</a>
		</p>
		<?php $this->load('page-limit'); ?>
		<table class="primary">
			<thead>
				<tr>
					<th></th>
					<?php if (get_filter('translation')) : ?>
						<th>Original</th>
						<th>Translation</th>
					<?php else : ?>
						<th>Term</th>
					<?php endif; ?>
					<th>Author</th>
					<th>Updated</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($list->terms as $term) : ?>
					<tr>
						<td>
							<?php if (!get_filter('translation')) : ?>
								<div class="btn-group">
									<a class="btn primary" href="term/<?php echo $term->id; ?>/form-meta" data-action="modal" data-target="#term-form">
										<i class="fa fa-edit"></i>
									</a>
								</div>
							<?php endif; ?>
						</td>
						<td>
							<div><?php echo $term->content; ?></div>
							<div class="small"><?php echo $term->descrip; ?></div>
						</td>
						<?php if (get_filter('translation')) : ?>
							<td>
								<?php if ($translation = $term->translation) : ?>
									<div><?php echo $translation->content; ?></div>
									<div class="small"><?php echo $translation->descrip; ?></div>
								<?php else : ?>
									<a href="term/<?php echo $term->id; ?>/form-meta/translation/<?php echo $language->id; ?>" data-target="#term-form" data-action="modal">Add Translation</a>
								<?php endif; ?>
							</td>
						<?php endif; ?>
						<td><?php echo $term->user->name; ?></td>
						<td><?php echo $term->updated; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php $this->pagination($list->terms->found); ?>
	</div>
	<div class="col-sm-12 col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9">
		<?php $this->load('card-meta', 'list', compact('list')); ?>
	</div>
</div>
<div class="modal card primary" id="term-form"></div>
