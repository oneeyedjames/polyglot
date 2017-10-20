<div class="card blue">
    <header><?php echo $role->title; ?></header>

    <p><?php echo $role->descrip; ?></p>

    <a class="btn sm green pull-right modal"
        href="role/<?php echo $role->id; ?>/form-permission"
        data-action="modal" data-target="#modal-form">
        <i class="fa fa-plus"></i>
    </a>
    <strong>Permissions</strong>
    <ul>
        <?php foreach ($role->permissions as $permission) : ?>
            <li>
                <?php
                    if ($permission->resource)
                        echo "$permission->resource/";

                    echo $permission->action;
                ?>
                <form action="role/<?php echo $role->id; ?>/remove-permission" method="POST" class="pull-right">
                    <?php $nonce = $this->create_nonce('remove-permission', 'role'); ?>
                    <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
                    <input type="hidden" name="permission" value="<?php echo $permission->id; ?>">

                    <button type="submit" class="btn sm red">
            			<i class="fa fa-trash"></i>
            		</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <footer>
        <form action="role/<?php echo $role->id; ?>/delete" method="POST">
            <?php $nonce = $this->create_nonce('delete', 'role'); ?>
            <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">

            <a class="btn blue" href="role/<?php echo $role->id; ?>/form-meta"
    			data-action="modal" data-target="#modal-form">
    			<i class="fa fa-edit"></i> Edit
    		</a>

    		<button type="submit" class="btn red">
    			<i class="fa fa-trash"></i> Delete
    		</button>
        </form>
	</footer>
</div>
