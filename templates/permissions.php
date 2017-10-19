<?php

global $database, $url_schema;

$sql = <<<SQL
SELECT role.name as role, permission.resource, permission.action, map.granted
FROM role CROSS JOIN permission
LEFT JOIN role_permission_map AS map
ON map.role_id = role.id
AND map.permission_id = permission.id
ORDER BY role.name, permission.resource
SQL;

$records = $database->query($sql);

$perms = $roles = array();

foreach ($records as $record) {
	$perms[$record->resource][$record->action][$record->role] = (bool) $record->granted;

	if (!in_array($record->role, $roles))
		$roles[] = $record->role;
}

//$roles = array_unique($roles);

?><div class="row">
	<div class="col-md-4">
		<form>
			<h3>Add New Permission</h3>

		</form>
	</div>
	<div class="col-md-8">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th scope="col">Permission</th>
					<?php foreach ($roles as $role) : ?>
						<th scope="col" style="text-align: center;">
							<input type="checkbox">
							<?php echo $role; ?>
						</th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($perms as $resource => $actions) : ?>
					<tr>
						<th class="bg-info" colspan="<?php echo count($roles) + 1; ?>">
							<?php echo $resource; ?>
						</th>
					</tr>
					<?php foreach ($actions as $action => $act_perms) : ?>
						<tr>
							<th>
								<input type="checkbox">
								<label><?php echo $resource . '::' . $action; ?></labeL>
							</th>
							<?php foreach ($roles as $role) : $granted = $act_perms[$role]; ?>
								<td id="<?php echo $resource . '-' . $action . '-' . $role; ?>" style="text-align: center;">
									<input type="checkbox"<?php if ($granted) echo ' checked="checked"'; ?>>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
