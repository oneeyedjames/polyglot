<header>Projects</header>
<h4><i class="fa fa-flag"></i> <?php echo $language->name; ?></h4>
<?php $label = ' Project' . ($language->projects->found > 1 ? 's' : ''); ?>
<strong><?php echo $language->projects->found . $label; ?></strong>
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
<footer>
    <button class="btn cancel">Close</button>
</footer>
