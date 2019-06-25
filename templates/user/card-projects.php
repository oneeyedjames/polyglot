<div class="modal card info" id="modal-card-projects">
    <header>
        <i class="fa fa-user"></i> <?php echo $user->alias; ?>
        <a class="cancel pull-right"><i class="fa fa-close"></i></a>
    </header>
    <strong><i class="fa fa-sitemap"></i> Projects</strong>
    <ul>
        <?php foreach ($user->projects as $project) : ?>
            <li>
                <a href="project/<?php echo $project->id; ?>">
                    <?php echo $project->title; ?>
                </a>
            </li>
        <?php endforeach; ?>
        <?php if ($user->projects->found > count($user->projects)) : ?>
            <li><em>more</em></li>
        <?php endif; ?>
    </ul>
</div>
