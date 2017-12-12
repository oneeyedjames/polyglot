<?php $delete_nonce = $this->create_nonce('delete', 'language'); ?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">Languages</li>
</ol>
<h2><i class="fa fa-flag"></i> Languages</h2>
<p>
	<a href="language/form-meta" target="#modal-card" class="btn success"
		data-action="modal" data-target="#modal-form-language">
		<i class="fa fa-plus"></i> Add New Language
	</a>
</p>
<p><?php $this->load('page-limit'); ?></p>
<table class="primary">
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
						<a href="language/<?php echo $language->id; ?>/form-meta" target="#modal-card"
							class="btn primary" data-action="modal" data-target="#modal-form-language">
							<i class="fa fa-edit"></i>
						</a>
			            <input type="hidden" name="nonce" value="<?php echo $delete_nonce; ?>">
						<button type="submit" class="btn danger">
			    			<i class="fa fa-trash"></i>
			    		</button>
					</form>
				</td>
				<td><?php echo $language->name; ?></td>
				<td><?php echo $language->code; ?></td>
				<td>
					<?php if ($language->projects->found) :
						$label = ' Project' . ($language->projects->found > 1 ? 's' : ''); ?>
						<a href="language/<?php echo $language->id; ?>/card-projects" target="#modal-card"
							data-action="modal" data-target="#modal-card-projects">
							<?php echo $language->projects->found . $label; ?>
						</a>
					<?php else : ?>
						<em>No Projects</em>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($language->users->found) :
						$label = ' User' . ($language->users->found > 1 ? 's' : ''); ?>
						<a href="language/<?php echo $language->id; ?>/card-users" target="#modal-card"
							data-action="modal" data-target="#modal-card-users">
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
