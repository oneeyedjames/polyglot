<header>
    <i class="fa fa-folder-open"></i> <?php echo $project->title; ?>
    <a href="#" class="cancel pull-right"><i class="fa fa-close"></i></a>
</header>
<strong><i class="fa fa-file"></i> Documents</strong>
<ul>
    <?php foreach ($project->documents as $document) : ?>
        <li>
            <a href="document/<?php echo $document->id; ?>">
                <?php echo $document->title; ?>
            </a>
        </li>
    <?php endforeach; ?>
    <?php if ($project->documents->found > count($project->documents)) : ?>
        <li><em>more</em></li>
    <?php endif; ?>
</ul>
