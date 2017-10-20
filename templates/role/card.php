<div class="card blue">
    <header>
        <!-- <a href="role/<?php echo $role->id; ?>"> -->
            <?php echo $role->title; ?>
        <!-- </a> -->
    </header>

    <p><?php echo $role->descrip; ?></p>

    <!-- <a class="btn sm green pull-right">
        <i class="fa fa-plus"></i>
    </a> -->
    <strong>Permissions</strong>
    <ul>
        <?php foreach ($role->permissions as $permission) : ?>
            <li>
                <?php
                    if ($permission->resource)
                        echo "$permission->resource/";

                    echo $permission->action;
                ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <footer>
        <?php $nonce = $this->create_nonce('delete', 'role'); ?>
        <form action="role/<?php echo $role->id; ?>/delete" method="POST">
            <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
    		<a href="role/<?php echo $role->id; ?>/form-meta" class="btn blue"
    			data-action="modal" data-target="#role-form">
    			<i class="fa fa-edit"></i> Edit
    		</a>
    		<button type="submit" class="btn red">
    			<i class="fa fa-trash"></i> Delete
    		</button>
        </form>
	</footer>
</div>
