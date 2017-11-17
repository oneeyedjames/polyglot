<header>Users</header>
<h4><i class="fa fa-flag"></i> <?php echo $language->name; ?></h4>
<?php $label = ' User' . ($language->users->found > 1 ? 's' : ''); ?>
<strong><?php echo $language->users->found . $label; ?></strong>
<ul>
    <?php foreach ($language->users as $user) : ?>
        <li>
            <a href="user/<?php echo $user->id; ?>">
                <?php echo $user->name; ?>
            </a>
        </li>
    <?php endforeach; ?>
    <?php if ($language->users->found > count($language->users)) : ?>
        <li><em>more</em></li>
    <?php endif; ?>
</ul>
<footer>
    <button class="btn cancel">Close</button>
</footer>
