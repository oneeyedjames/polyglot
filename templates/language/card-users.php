<div class="modal card info" id="modal-card-users">
    <header>
        <i class="fa fa-flag"></i> <?php echo $language->name; ?>
        <a class="pull-right cancel"><i class="fa fa-close"></i></a>
    </header>
    <strong><i class="fa fa-user"></i> Users</strong>
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
</div>
