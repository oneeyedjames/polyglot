<header>Users</header>
<h4><i class="fa fa-folder-open"></i> <?php echo $project->title; ?></h4>
<?php $label = ' User' . ($project->users->found > 1 ? 's' : ''); ?>
<strong><?php echo $project->users->found . $label; ?></strong>
<ul>
    <?php foreach ($project->users as $user) : ?>
        <li>
            <a href="user/<?php echo $user->id; ?>">
                <?php echo $user->name; ?>
            </a>
        </li>
    <?php endforeach; ?>
    <?php if ($project->users->found > count($project->users)) : ?>
        <li><em>more</em></li>
    <?php endif; ?>
</ul>
<footer>
    <button class="btn cancel">Close</button>
</footer>
