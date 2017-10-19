<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">Users</li>
</ol>
<h2><i class="fa fa-user"></i> Users</h2>
<p>
	<a class="btn green" data-action="modal" data-target="#user-form" href="user/form-meta">
		<i class="fa fa-plus"></i> Add New User
	</a>
</p>
<div class="row">
	<?php foreach ($users as $user) : ?>
		<div class="col-md-6 col-lg-4">
			<?php $this->load('card', 'user', compact('user')); ?>
		</div>
	<?php endforeach; ?>
</div>
<div class="card blue modal" id="user-form"></div>
