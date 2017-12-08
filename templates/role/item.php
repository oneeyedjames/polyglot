<?php $nonce = $this->create_nonce('remove-permission', 'role'); ?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li><a href="roles">Roles</a></li>
	<li class="active"><?php echo $role->title; ?></li>
</ol>
<h2><i class="fa fa-folder-open"></i> <?php echo $role->title; ?></h2>
<p class="lead"><?php echo $role->descrip; ?></p>
<h3><i class="fa fa-key"></i> Permissions</h3>
<p>
	<a href="role/<?php echo $role->id; ?>/form-permission" target="#modal-card"
        class="btn success" data-action="modal" data-target="#modal-form-permission">
		<i class="fa fa-plus"></i> Add Permission
	</a>
</p>
<p><?php $this->load('page-limit'); ?></p>
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
				<td style="width: 2.5em">
					<form action="role/<?php echo $role->id; ?>/remove-permission" method="POST"
						data-confirm="Are you sure you want to remove this permission?">
						<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
						<input type="hidden" name="permission" value="<?php echo $permission->id; ?>">
						<button type="submit" class="danger">
							<i class="fa fa-minus"></i>
						</button>
					</form>
				</td>
                <td><?php echo $permission->resource; ?></td>
                <td><?php echo $permission->action; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<p><?php $this->pagination($role->permissions->found); ?></p>
<div id="modal-card"></div>
