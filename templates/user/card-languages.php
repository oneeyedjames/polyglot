<header>Languages</header>
<h4><i class="fa fa-user"></i> <?php echo $user->name; ?></h4>
<?php $label = ' Language' . ($user->languages->found > 1 ? 's' : ''); ?>
<strong><?php echo $user->languages->found . $label; ?></strong>
<ul>
    <?php foreach ($user->languages as $language) : ?>
        <li><?php echo $language->name; ?></li>
    <?php endforeach; ?>
    <?php if ($user->languages->found > count($user->languages)) : ?>
        <li><em>more</em></li>
    <?php endif; ?>
</ul>
<footer>
    <button class="btn cancel">Close</button>
</footer>
