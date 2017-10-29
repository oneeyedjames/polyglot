<div class="card blue">
	<header>
		<a href="language/<?php echo $language->id; ?>"><?php echo $language->name; ?></a>
	</header>
	<strong>Locale</strong>
	<p><?php echo $language->code; ?></p>
	<div class="row">
		<div class="col-md-6">
			<strong>Projects (<?php echo $language->projects->found; ?>)</strong>
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
		<div class="col-md-6">
			<strong>Users (<?php echo $language->users->found; ?>)</strong>
			<ul>
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
			</ul>
		</div>
	</div>
	<footer>
		<form action="language/<?php echo $language->id; ?>/delete" method="POST">
            <?php $nonce = $this->create_nonce('delete', 'language'); ?>
            <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">

			<a href="language/<?php echo $language->id; ?>/form-meta" class="btn blue"
				data-action="modal" data-target="#modal-form">
				<i class="fa fa-edit"></i> Edit
			</a>

    		<button type="submit" class="btn red">
    			<i class="fa fa-trash"></i> Delete
    		</button>
        </form>
	</footer>
</div>
