<header>Term Lists</header>
<h4><i class="fa fa-folder-open"></i> <?php echo $project->title; ?></h4>
<?php $label = ' Term List' . ($project->lists->found > 1 ? 's' : ''); ?>
<strong><?php echo $project->lists->found . $label; ?></strong>
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
<footer>
    <button class="btn cancel">Close</button>
</footer>
