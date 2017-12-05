<div class="card primary">
    <header><?php echo $role->title; ?></header>

    <p><?php echo $role->descrip; ?></p>

    <?php $form_id = "remove-role-permission-$role->id"; ?>
    <form action="role/<?php echo $role->id; ?>/remove-permission" method="POST"
        id="<?php echo $form_id; ?>" data-confirm="Are you sure you want to remove this permission?">
        <?php $nonce = $this->create_nonce('remove-permission', 'role'); ?>
        <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
        <input type="hidden" name="permission">
    </form>

    <a class="txt-btn success pull-right"
        href="role/<?php echo $role->id; ?>/form-permission"
        data-action="modal" data-target="#modal-form">
        <i class="fa fa-plus"></i> Add
    </a>
    <strong>Permissions</strong>
    <ul>
        <?php foreach ($role->permissions as $permission) : ?>
            <li>
                <div><?php
                    if ($permission->resource)
                        echo "$permission->resource/";

                    echo $permission->action;
                ?></div>
                <a class="txt-btn danger"
                    data-action="submit" data-target="#<?php echo $form_id ?>"
                    data-input-permission="<?php echo $permission->id; ?>">
        			<i class="fa fa-minus"></i> Remove
        		</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <footer>
        <form action="role/<?php echo $role->id; ?>/delete" method="POST">
            <?php $nonce = $this->create_nonce('delete', 'role'); ?>
            <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">

            <a class="btn primary" href="role/<?php echo $role->id; ?>/form-meta"
    			data-action="modal" data-target="#modal-form">
    			<i class="fa fa-edit"></i> Edit
    		</a>

    		<button type="submit" class="btn danger">
    			<i class="fa fa-trash"></i> Delete
    		</button>
        </form>
	</footer>
</div>
