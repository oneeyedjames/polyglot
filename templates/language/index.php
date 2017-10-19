<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">Languages</li>
</ol>
<h2><i class="fa fa-flag"></i> Languages</h2>
<p>
	<a class="btn green" data-action="modal" data-target="#language-form" href="language/form-meta">
		<i class="fa fa-plus"></i> Add New Language
	</a>
</p>
<div class="row">
	<?php foreach ($languages as $language) : ?>
		<div class="col-md-6 col-lg-4">
			<?php $this->load('card', 'language', compact('language')); ?>
		</div>
	<?php endforeach; ?>
</div>
<div class="card modal col-md-8 col-lg-6 blue" id="language-form"></div>
