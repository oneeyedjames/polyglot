<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">Languages</li>
</ol>
<h2><i class="fa fa-flag"></i> Languages</h2>
<p>
	<a class="btn green" href="language/form-meta"
		data-action="modal" data-target="#modal-form">
		<i class="fa fa-plus"></i> Add New Language
	</a>
</p>
<table class="blue striped">
	<thead>
		<tr>
			<th></th>
			<th>Name</th>
			<th>Code</th>
			<th># Projects</th>
			<th># Users</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($languages as $language) : ?>
			<tr>
				<td>
					<form action="language/<?php echo $language->id; ?>/delete" method="POST" class="btn-group pull-left"
						data-confirm="Are you sure you want to delete this language?">
						<a href="language/<?php echo $language->id; ?>/form-meta" class="btn blue"
							data-action="modal" data-target="#modal-form">
							<i class="fa fa-edit"></i>
						</a>

			    		<?php $nonce = $this->create_nonce('delete', 'language'); ?>
			            <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">

						<button type="submit" class="btn red">
			    			<i class="fa fa-trash"></i>
			    		</button>
					</form>
				</td>
				<td><?php echo $language->name; ?></td>
				<td><?php echo $language->code; ?></td>
				<td>
					<a style="cursor: pointer" data-action="collapse"
						data-target="#language-<?php echo $language->id; ?>-projects">
						<?php echo $language->projects->found; ?> Projects
					</a>
					<div id="language-<?php echo $language->id; ?>-projects" class="collapsed">
						<ul>
							<?php foreach ($language->projects as $project) : ?>
								<li>
									<a href="project/<?php echo $project->id; ?>">
										<?php echo $project->title; ?>
									</a>
								</li>
							<?php endforeach; ?>
							<?php if ($language->projects->found > count($language->projects)) : ?>
								<li><em>more</em></li>
							<?php endif; ?>
						</ul>
					</div>
				</td>
				<td>
					<a style="cursor: pointer" data-action="collapse"
						data-target="#language-<?php echo $language->id; ?>-users">
						<?php echo $language->users->found; ?> Users
					</a>
					<div id="language-<?php echo $language->id; ?>-users" class="collapsed">
						<?php foreach ($language->users as $user) : ?>
							<li>
								<a href="user/<?php echo $user->id; ?>">
									<?php echo $user->name; ?>
								</a>
							</li>
						<?php endforeach; ?>
						<?php if ($language->users->found > count($language->users)) : ?>
							<li><em>more</em></li>
						<?php endif; ?>
					</div>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<p><?php $this->pagination($languages->found); ?></p>
<div class="card modal col-md-8 col-lg-6 blue" id="modal-form"></div>
