<?php

$nonce = $this->create_nonce('add-permission', 'role');

foreach ($permissions as $permission)
    $actions[$permission->resource][] = "<option value='$permission->id'>$permission->action</option>";

$default_options = $actions[$permissions[0]->resource];

?>
<form action="role/<?php echo $role->id; ?>/add-permission" method="POST">
    <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
    <header>Add Permission</header>

    <p>Add permission for: <span class="lbl yellow"><?php echo $role->title; ?></span></p>

    <label>Resource</label>
    <select id="permission-resource">
        <?php foreach ($resources as $resource) : ?>
            <option value="<?php echo $resource; ?>"><?php echo $resource; ?></option>
        <?php endforeach; ?>
    </select>

    <label>Action</label>
    <select id="permission-action" name="permission">
        <?php echo implode(PHP_EOL, $default_options); ?>
    </select>

    <?php foreach ($actions as $resource => $options) : ?>
        <div id="permission-resource-<?php echo $resource; ?>" style="display: none;">
            <?php echo implode(PHP_EOL, $options); ?>
        </div>
    <?php endforeach; ?>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#permission-resource').change(function() {
                var group = $('#permission-resource-' + $(this).val());
                $('#permission-action').html(group.html());
            });
        });
    </script>

    <footer>
		<button type="submit" class="btn blue">Save</button>
		<button type="button" class="btn cancel">Cancel</button>
	</footer>
</form>
