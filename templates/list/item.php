<?php

$delete_list_nonce = $this->create_nonce('delete', 'list');
$delete_term_nonce = $this->create_nonce('delete', 'term');

?>
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
			<i class="fa fa-list"></i> <?php echo $list->title; ?>
			<form action="list/<?php echo $list->id; ?>/delete" method="POST" class="btn-group pull-right"
				data-confirm="Are you sure you want to delete this term list?">
				<a href="lists/<?php echo $list->id; ?>/form-meta" target="#modal-card"
					class="btn primary" data-action="modal" data-target="#modal-form-meta">
					<i class="fa fa-edit"></i> Edit
				</a>
				<input type="hidden" name="nonce" value="<?php echo $delete_list_nonce; ?>">
				<button type="submit" class="btn danger">
					<i class="fa fa-trash"></i> Delete
				</button>
			</form>
		</h2>
		<p class="lead"><?php echo $list->descrip; ?></p>
		<h3><i class="fa fa-terminal"></i> Terms</h3>
		<p class="pull-right">
			<a href="list/<?php echo $list->id; ?>/terms/form-meta" target="#modal-card"
				class="btn success" data-action="modal" data-target="#modal-form-meta">
				<i class="fa fa-plus"></i> Add New Term
			</a>
		</p>
		<p><?php $this->load('page-limit'); ?></p>
		<table class="primary">
			<thead>
				<tr>
					<th class="snap"></th>
					<th><?php echo get_filter('translation') ? 'Original' : 'Term'; ?></th>
					<?php if (get_filter('translation')) : ?>
						<th>Translation</th>
					<?php endif; ?>
					<th>Author</th>
					<th>Created</th>
					<th>Updated</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($list->terms as $term) : ?>
					<tr>
						<td class="snap">
							<?php if (!get_filter('translation') || $term->translation) :
								$edit_term = $term->translation ?: $term; ?>
								<form action="term/<?php echo $edit_term->id; ?>/delete" method="POST" class="btn-group pull-left"
									data-confirm="Are you sure you want to delete this term?">
									<a href="term/<?php echo $edit_term->id; ?>/form-meta" target="#modal-card"
										class="btn primary" data-action="modal" data-target="#modal-form-meta">
										<i class="fa fa-edit"></i>
									</a>
									<input type="hidden" name="nonce" value="<?php echo $delete_term_nonce; ?>">
									<button type="submit" class="btn danger">
										<i class="fa fa-trash"></i>
									</button>
								</form>
							<?php else : ?>
								<div class="btn-group pull-left">
									<a class="btn primary disabled">
										<i class="fa fa-edit"></i>
									</a>
									<a class="btn danger disabled">
										<i class="fa fa-trash"></i>
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
									<a href="term/<?php echo $term->id; ?>/form-meta/translation/<?php echo $language->id; ?>"
										target="#modal-card" class="btn sm text success"
										data-action="modal" data-target="#modal-form-meta">
										<i class="fa fa-plus"></i> Add Translation
									</a>
								<?php endif; ?>
							</td>
							<td><?php echo $term->translation->user->name; ?></td>
							<td><?php echo $term->translation->created; ?></td>
							<td><?php echo $term->translation->updated; ?></td>
						<?php else : ?>
							<td><?php echo $term->user->name; ?></td>
							<td><?php echo $term->created; ?></td>
							<td><?php echo $term->updated; ?></td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<p><?php $this->pagination($list->terms->found); ?></p>
	</div>
	<div class="col-sm-12 col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9">
		<?php
			$this->load('item-meta', 'list', compact('list'));
			$this->load('item-translation', 'list', compact('list'));
		?>
	</div>
</div>
