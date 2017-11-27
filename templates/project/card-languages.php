<header>Languages</header>
<h4><i class="fa fa-folder-open"></i> <?php echo $project->title; ?></h4>
<?php $label = ' Language' . ($project->languages->found > 1 ? 's' : ''); ?>
<strong><?php echo $project->languages->found . $label; ?></strong>
<ul>
    <?php foreach ($project->languages as $language) : ?>
        <li><?php echo $language->name; ?></li>
    <?php endforeach; ?>
    <?php if ($project->languages->found > count($project->languages)) : ?>
        <li><em>more</em></li>
    <?php endif; ?>
</ul>
<footer>
    <button class="btn cancel">Close</button>
</footer>
