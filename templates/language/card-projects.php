<div class="modal card info" id="modal-card-projects">
    <header>
        <i class="fa fa-flag"></i> <?php echo $language->name; ?>
        <a class="pull-right cancel"><i class="fa fa-close"></i></a>
    </header>
    <strong><i class="fa fa-sitemap"></i> Projects</strong>
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
