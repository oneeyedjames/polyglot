<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">Roles</li>
</ol>
<h2><i class="fa fa-group"></i> Roles</h2>
<p>
	<a class="btn green" href="role/form-meta"
		data-action="modal" data-target="#modal-form">
		<i class="fa fa-plus"></i> Add New Role
	</a>
</p>
<div class="row">
	<?php foreach ($roles as $role) : ?>
		<div class="col-md-6 col-lg-4">
			<?php $this->load('card', 'role', compact('role')); ?>
		</div>
	<?php endforeach; ?>
</div>
<div class="card blue modal" id="modal-form"></div>
