<div class="modal card info" id="modal-card-lists">
    <header>
        <i class="fa fa-sitemap"></i> <?php echo $project->title; ?>
        <a href="#" class="cancel pull-right"><i class="fa fa-close"></i></a>
    </header>
    <strong><i class="fa fa-list"></i> Term Lists</strong>
    <ul>
        <?php foreach ($project->lists as $list) : ?>
            <li>
                <a href="document/<?php echo $list->id; ?>">
                    <?php echo $list->title; ?>
                </a>
            </li>
        <?php endforeach; ?>
        <?php if ($project->lists->found > count($project->lists)) : ?>
            <li><em>more</em></li>
        <?php endif; ?>
    </ul>
</div>
