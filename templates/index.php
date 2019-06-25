<ol class="breadcrumb">
	<li class="active"><i class="fa fa-home"></i> Home</li>
</ol>
<div class="row">
	<div class="col-md-4 col-lg-3">
		<div class="card info collapsible">
			<header><i class="fa fa-sitemap"></i> My Projects</header>
			<ul>
				<?php foreach ($projects as $project) : ?>
					<li><a href="project/<?php echo $project->id; ?>"><?php echo $project->title; ?></a></li>
				<?php endforeach; ?>
			</ul>
			<?php if ($projects->found > count($projects)) : ?>
				<div class="align-right">
					<a href="projects" class="btn sm text primary">
						See More <i class="fa fa-chevron-right"></i>
					</a>
				</div>
			<?php endif; ?>
		</div>
		<div class="card info collapsible">
			<header><i class="fa fa-flag"></i> My Languages</header>
			<ul>
				<?php foreach ($languages as $language) : ?>
					<li><?php echo $language->name; ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="col-md-8 col-lg-9">
		<h2><i class="fa fa-home"></i> Home</h2>
	</div>
</div>
