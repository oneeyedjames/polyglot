<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">Permissions</li>
</ol>
<h2><i class="fa fa-lock"></i> Permissions</h2>
<p>
	<a class="btn green" href="permission/form-meta"
		data-action="modal" data-target="#modal-form">
		<i class="fa fa-plus"></i> Add New Permission
	</a>
	<a class="txt-btn blue" href="permissions">Permissions</a>
</p>

<?php ksort($resources); ?>
<table class="blue">
    <thead>
        <tr>
            <th>Resource</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($resources as $resource => $resource_meta) :
            sort($resource_meta['actions']);
            foreach ($resource_meta['actions'] as $action) : ?>
            <tr>
                <td><?php echo $resource; ?></td>
                <td><?php echo $action; ?></td>
            </tr>
        <?php endforeach; endforeach; ?>
    </tbody>
</table>
