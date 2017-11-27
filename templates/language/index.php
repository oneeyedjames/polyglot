<?php $delete_nonce = $this->create_nonce('delete', 'language'); ?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">Languages</li>
</ol>
<h2><i class="fa fa-flag"></i> Languages</h2>
<p>
	<a class="btn green" href="language/form-meta"
		data-action="modal" data-target="#modal-form">
		<i class="fa fa-plus"></i> Add New Language
	</a>
</p>
<p><?php $this->load('page-limit'); ?></p>
<table class="blue striped">
	<thead>
		<tr>
			<th class="snap"></th>
			<th>Name</th>
			<th>Code</th>
			<th># Projects</th>
			<th># Users</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($languages as $language) : ?>
			<tr>
				<td class="snap">
					<form action="language/<?php echo $language->id; ?>/delete" method="POST" class="btn-group pull-left"
						data-confirm="Are you sure you want to delete this language?">
						<a href="language/<?php echo $language->id; ?>/form-meta" class="btn blue"
							data-action="modal" data-target="#modal-form">
							<i class="fa fa-edit"></i>
						</a>

			            <input type="hidden" name="nonce" value="<?php echo $delete_nonce; ?>">

						<button type="submit" class="btn red">
			    			<i class="fa fa-trash"></i>
			    		</button>
					</form>
				</td>
				<td><?php echo $language->name; ?></td>
				<td><?php echo $language->code; ?></td>
				<td>
					<?php if ($language->projects->found) :
						$label = ' Project' . ($language->projects->found > 1 ? 's' : ''); ?>
						<a href="language/<?php echo $language->id; ?>/card-projects"
							data-action="modal" data-target="#modal-form">
							<?php echo $language->projects->found . $label; ?>
						</a>
					<?php else : ?>
						<em>No Projects</em>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($language->users->found) :
						$label = ' User' . ($language->users->found > 1 ? 's' : ''); ?>
						<a href="language/<?php echo $language->id; ?>/card-users"
							data-action="modal" data-target="#modal-form">
							<?php echo $language->users->found . $label; ?>
						</a>
					<?php else : ?>
						<em>No Users</em>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<p><?php $this->pagination($languages->found); ?></p>
<div class="card modal col-md-8 col-lg-6 cyan" id="modal-form"></div>
