<header>Documents</header>
<h4><i class="fa fa-folder-open"></i> <?php echo $project->title; ?></h4>
<?php $label = ' Document' . ($project->documents->found > 1 ? 's' : ''); ?>
<strong><?php echo $project->documents->found . $label; ?></strong>
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
<footer>
    <button class="btn cancel">Close</button>
</footer>
