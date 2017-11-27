<header>Projects</header>
<h4><i class="fa fa-user"></i> <?php echo $user->name; ?></h4>
<?php $label = ' Project' . ($user->projects->found > 1 ? 's' : ''); ?>
<strong><?php echo $user->projects->found . $label; ?></strong>
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
<footer>
    <button class="btn cancel">Close</button>
</footer>
