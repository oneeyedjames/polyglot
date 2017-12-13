<?php

$delete_nonce = $this->create_nonce('delete', 'role');
$permission_nonce = $this->create_nonce('remove-permission', 'role');

?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li><a href="roles">Roles</a></li>
	<li class="active"><?php echo $role->title; ?></li>
</ol>
<h2 class="page-title">
	<i class="fa fa-folder-open"></i> <?php echo $role->title; ?>
	<form action="role/<?php echo $role->id; ?>/delete" method="POST" class="btn-group pull-right"
		data-confirm="Are you sure you want to delete this role?">
		<a href="role/<?php echo $role->id; ?>/form-meta" target="#modal-card"
			class="btn primary" data-action="modal" data-target="#modal-form-meta">
			<i class="fa fa-edit"></i> Edit
		</a>
		<input type="hidden" name="nonce" value="<?php echo $delete_list_nonce; ?>">
		<button type="submit" class="btn danger">
			<i class="fa fa-trash"></i> Delete
		</button>
	</form>
</h2>
<p class="lead"><?php echo $role->descrip; ?></p>
<h3><i class="fa fa-key"></i> Permissions</h3>
<div class="btn-toolbar">
	<a href="role/<?php echo $role->id; ?>/form-permission" target="#modal-card"
        class="btn success" data-action="modal" data-target="#modal-form-permission">
		<i class="fa fa-plus"></i> Add Permission
	</a>
	<div class="pull-right"><?php $this->load('page-limit'); ?></div>
</div>
<table class="primary">
    <thead>
        <tr>
			<th></th>
            <th>Resource</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($role->permissions as $permission) : ?>
            <tr>
				<td class="snap">
					<a class="btn danger" data-action="submit" data-target="#remove-permission-form"
						data-input-permission="<?php echo $permission->id; ?>" style="white-space: nowrap">
						<i class="fa fa-minus"></i> Remove
					</a>
				</td>
                <td><?php echo $permission->resource; ?></td>
                <td><?php echo $permission->action; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<form action="role/<?php echo $role->id; ?>/remove-permission" method="POST" id="remove-permission-form"
	data-confirm="Are you sure you want to remove this permission?">
	<input type="hidden" name="nonce" value="<?php echo $permission_nonce; ?>">
	<input type="hidden" name="permission">
</form>
<div class="btn-toolbar">
	<div class="pull-right"><?php $this->pagination($role->permissions->found); ?></div>
</div>
