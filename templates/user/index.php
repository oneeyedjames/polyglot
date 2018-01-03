<?php $delete_nonce = $this->create_nonce('delete', 'user'); ?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">Users</li>
</ol>
<h2 class="page-title"><i class="fa fa-user"></i> Users</h2>
<div class="btn-toolbar">
	<a href="user/form-meta" target="#modal-card" class="btn success"
		data-action="modal" data-target="#modal-form-meta">
		<i class="fa fa-plus"></i> Add New User
	</a>
	<div class="pull-right"><?php $this->load('page-limit'); ?></div>
</div>
<table class="primary">
	<thead>
		<tr>
			<th class="snap"></th>
			<th>User</th>
			<th>Email</th>
			<th>Admin</th>
			<th>Projects</th>
			<th>Languages</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($users as $user) : ?>
			<tr>
				<td class="snap">
					<form action="user/<?php echo $user->id; ?>/delete" method="POST" class="btn-group pull-left"
						data-confirm="Are you sure you want to delete this user?">
						<a href="user/<?php echo $user->id; ?>/form-meta" target="#modal-card"
							class="btn primary" data-action="modal" data-target="#modal-form-meta">
							<i class="fa fa-edit"></i>
						</a>
			            <input type="hidden" name="nonce" value="<?php echo $delete_nonce; ?>">
						<button type="submit" class="btn danger">
			    			<i class="fa fa-trash"></i>
			    		</button>
					</form>
				</td>
				<td><a href="user/<?php echo $user->id; ?>"><?php echo $user->alias; ?></a></td>
				<td><?php echo $user->email; ?></td>
				<td>
					<?php $icon = $user->admin ? 'check' : 'times'; ?>
					<i class="fa fa-<?php echo $icon; ?>"></i>
				</td>
				<td>
					<?php if ($user->projects->found) :
						$label = ' Project' . ($user->projects->found > 1 ? 's' : ''); ?>
						<a href="user/<?php echo $user->id; ?>/card-projects" target="#modal-card"
							data-action="modal" data-target="#modal-card-projects">
							<?php echo $user->projects->found . $label; ?>
						</a>
					<?php else : ?>
						<em>No Projects</em>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($user->languages->found) :
						$label = ' Language' . ($user->languages->found > 1 ? 's' : ''); ?>
						<a href="user/<?php echo $user->id; ?>/card-languages" target="#modal-card"
							data-action="modal" data-target="#modal-card-languages">
							<?php echo $user->languages->found . $label; ?>
						</a>
					<?php else : ?>
						<em>No Languages</em>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<div class="btn-toolbar">
	<div class="pull-right"><?php $this->pagination($users->found); ?></div>
</div>
