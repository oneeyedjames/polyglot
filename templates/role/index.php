<?php $delete_nonce = $this->create_nonce('delete', 'role'); ?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">Roles</li>
</ol>
<h2><i class="fa fa-group"></i> Roles</h2>
<p>
	<a href="role/form-meta" target="#modal-card" class="btn success"
		data-action="modal" data-target="#modal-form-meta">
		<i class="fa fa-plus"></i> Add New Role
	</a>
</p>
<p><?php $this->load('page-limit'); ?></p>
<table class="primary">
	<thead>
		<tr>
			<th class="snap"></th>
			<th>Role</th>
			<th>Description</th>
			<th>Permissions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($roles as $role) : ?>
			<tr>
				<td class="snap">
					<form action="role/<?php echo $role->id; ?>/delete" method="POST" class="btn-group pull-left"
						data-confirm="Are you sure you want to delete this role?">
			            <a href="role/<?php echo $role->id; ?>/form-meta" target="#modal-card"
			    			class="btn primary" data-action="modal" data-target="#modal-form-meta">
			    			<i class="fa fa-edit"></i>
			    		</a>

						<input type="hidden" name="nonce" value="<?php echo $delete_nonce; ?>">

			    		<button type="submit" class="btn danger">
			    			<i class="fa fa-trash"></i>
			    		</button>
			        </form>
				</td>
				<td><a href="roles/<?php echo $role->id; ?>"><?php echo $role->title; ?></a></td>
				<td><?php echo $role->descrip; ?></td>
				<td>
					<?php if ($role->permissions->found) :
						$label = ' Permission' . ($role->permissions->found > 1 ? 's' : ''); ?>
						<a href="role/<?php echo $role->id; ?>/card-permissions" target="#modal-card"
							data-action="modal" data-target="#modal-card-permissions">
							<?php echo $role->permissions->found . $label; ?>
						</a>
					<?php else : ?>
						<em>No Permissions</em>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<p><?php $this->pagination($roles->found); ?></p>
<div id="modal-card"></div>
