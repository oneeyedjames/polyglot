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
