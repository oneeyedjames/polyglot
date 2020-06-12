<div class="modal card info" id="modal-card-permissions">
	<header>
		<i class="fa fa-users"></i> Roles
		<a class="cancel pull-right"><i class="fa fa-close"></i></a>
	</header>
	<strong><i class="fa fa-key"></i> Permissions</strong>
	<ul>
		<?php foreach ($role->permissions as $permission) : ?>
			<li><?php
				if ($permission->resource)
					echo "$permission->resource/";

				echo $permission->action;
			?></li>
		<?php endforeach; ?>
	</ul>
</div>
