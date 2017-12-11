<?php $delete_nonce = $this->create_nonce('delete', 'user'); ?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="users">Users</a></li>
	<li class="active"><?php echo $user->name; ?></li>
</ol>
<div class="row">
	<div class="col-sm-12 col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
        <h2>
            <i class="fa fa-user"></i> <?php echo $user->name; ?>
            <form action="user/<?php echo $user->id; ?>/delete" method="POST" class="btn-group pull-right">
				<a href="users/<?php echo $user->id; ?>/form-meta" target="#modal-card"
					class="btn primary" data-action="modal" data-target="#modal-form-meta">
					<i class="fa fa-edit"></i> Edit
				</a>
				<input type="hidden" name="nonce" value="<?php echo $delete_nonce; ?>">
				<button type="submit" class="btn danger">
					<i class="fa fa-trash"></i> Delete
				</button>
			</form>
        </h2>
    </div>
    <div class="col-sm-12 col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9">
		<div class="card info">
            <header>
                <i class="fa fa-flag"></i> Languages
                <a href="user/<?php echo $user->id; ?>/form-language" target="#modal-card"
                    class="pull-right" data-action="modal" data-target="#modal-form-language">
                    <i class="fa fa-plus"></i> Add
                </a>
            </header>
            <ul>
                <?php foreach ($user->languages as $language) : ?>
                    <li>
                        <strong><?php echo $language->name; ?></strong>
                        <a class="btn sm text danger pull-right"
        					data-action="submit" data-target="#remove-language-form"
        					data-input-language="<?php echo $language->id; ?>">
        					<i class="fa fa-minus"></i> Remove
        				</a>
                        <div><?php echo $language->code; ?></div>
                    </li>
                <?php endforeach; ?>
                <?php if ($user->languages->found > count($user->languages)) : ?>
                    <li><em>more</em></li>
                <?php endif; ?>
            </ul>
            <form action="user/<?php echo $user->id; ?>/remove-language" method="POST" id="remove-language-form"
            	data-confirm="Are you sure you want to remove this language from the user?">
            	<?php $nonce = $this->create_nonce('remove-language', 'user'); ?>
            	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
            	<input type="hidden" name="language">
            </form>
        </div>
        <div class="card info">
            <header>
                <i class="fa fa-folder-open"></i> Projects
                <a href="user/<?php echo $user->id; ?>/form-project" target="#modal-card"
                    class="pull-right" data-action="modal" data-target="#modal-form-project">
                    <i class="fa fa-plus"></i> Add
                </a>
            </header>
            <ul>
                <?php foreach ($user->projects as $project) : ?>
                    <li>
                        <a href="project/<?php echo $project->id; ?>">
                            <strong><?php echo $project->title; ?></strong>
                        </a>
                        <a class="btn sm text danger pull-right"
        					data-action="submit" data-target="#remove-project-form"
        					data-input-project="<?php echo $project->id; ?>">
        					<i class="fa fa-minus"></i> Remove
        				</a>
                        <div><?php echo $project->role->title; ?></div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <form action="user/<?php echo $user->id; ?>/remove-project" method="POST" id="remove-project-form"
            	data-confirm="Are you sure you want to remove this project from the user?">
            	<?php $nonce = $this->create_nonce('remove-project', 'user'); ?>
            	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
            	<input type="hidden" name="project">
            </form>
        </div>
    </div>
</div>
<div id="modal-card"></div>
