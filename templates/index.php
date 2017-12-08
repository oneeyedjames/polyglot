<ol class="breadcrumb">
	<li><i class="fa fa-home"></i> Home</li>
</ol>
<div class="row">
	<div class="col-md-6 col-lg-4">
		<h3><i class="fa fa-folder-open"></i> Projects</h3>
		<!--<p>
			<a class="btn success" data-action="modal" data-target="#project-form" href="project/form-meta">
				<i class="fa fa-plus"></i> Add New Project
			</a>
		</p>-->
		<?php foreach ($projects as $project) $this->load('card', 'project', compact('project')); ?>
		<a class="btn" href="projects">See More <i class="fa fa-chevron-right"></i></a>
	</div>
	<div class="col-md-6 col-lg-4">
		<h3><i class="fa fa-flag"></i> Languages</h3>
		<!--<p>
			<a class="btn success" data-action="modal" data-target="#project-form" href="language/form-meta">
				<i class="fa fa-plus"></i> Add New Language
			</a>
		</p>-->
		<?php foreach ($languages as $language) $this->load('card', 'language', compact('language')); ?>
		<a class="btn" href="languages">See More <i class="fa fa-chevron-right"></i></a>
	</div>
	<div class="col-md-6 col-lg-4">
		<h3><i class="fa fa-user"></i> Users</h3>
		<!--<p>
			<a class="btn success" data-action="modal" data-target="#project-form" href="users/form-meta">
				<i class="fa fa-plus"></i> Add New User
			</a>
		</p>-->
		<?php foreach ($users as $user) $this->load('card', 'user', compact('user')); ?>
		<a class="btn" href="users">See More <i class="fa fa-chevron-right"></i></a>
	</div>
</div>
<div class="modal card primary" id="project-form"></div>
<div class="modal card primary" id="language-form"></div>
<div class="modal card primary" id="user-form"></div>
