<?php

$nonce = $this->create_nonce('add-permission', 'role');

$actions = array();

ksort($resources);
foreach ($resources as $resource => $resource_meta) {
    $actions[$resource] = array();

    sort($resource_meta['actions']);
    foreach ($resource_meta['actions'] as $action) {
        $actions[$resource][] = "<option value='$action'>$action</option>";
    }
}

?>
<form action="role/<?php echo $role->id; ?>/add-permission" method="POST">
    <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
    <header>Add Permission</header>

    <p>Add permission for: <span class="lbl yellow"><?php echo $role->title; ?></span></p>

    <label>Resource</label>
    <select id="permission-resource" name="permission[resource]">
        <?php foreach (array_keys($resources) as $resource) : ?>
            <option value="<?php echo $resource; ?>"><?php echo $resource; ?></option>
        <?php endforeach; ?>
    </select>

    <label>Action</label>
    <select id="permission-action" name="permission[action]"></select>

    <?php foreach ($actions as $resource => $options) : ?>
        <div id="permission-resource-<?php echo $resource; ?>"
            class="permission-resource-actions">
            <?php echo implode(PHP_EOL, $options); ?>
        </div>
    <?php endforeach; ?>

    <footer>
		<button type="submit" class="btn blue">Save</button>
		<button type="button" class="btn cancel">Cancel</button>
	</footer>
</form>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        var group = $('.permission-resource-actions').first();
        $('#permission-action').html(group.html());

        $('#permission-resource').change(function() {
            var group = $('#permission-resource-' + $(this).val());
            $('#permission-action').html(group.html());
        });
    });
</script>
