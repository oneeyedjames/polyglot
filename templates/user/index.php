<?php $delete_nonce = $this->create_nonce('delete', 'user'); ?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">Users</li>
</ol>
<h2><i class="fa fa-user"></i> Users</h2>
<p>
	<a href="user/form-meta" target="#modal-card" class="btn success"
		data-action="modal" data-target="#modal-form-meta">
		<i class="fa fa-plus"></i> Add New User
	</a>
</p>
<p><?php $this->load('page-limit'); ?></p>
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
				<td><a href="user/<?php echo $user->id; ?>"><?php echo $user->name; ?></a></td>
				<td><?php echo $user->email; ?></td>
				<td><i class="fa fa-check"></i></td>
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
					<a href="user/<?php echo $user->id; ?>/form-projects" target="#modal-card"
						data-action="modal" data-target="#modal-form-projects">
						<i class="fa fa-edit"></i>
					</a>
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
					<a href="user/<?php echo $user->id; ?>/form-languages" target="#modal-card"
						data-action="modal" data-target="#modal-form-languages">
						<i class="fa fa-edit"></i>
					</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<p><?php $this->pagination($users->found); ?></p>
<div id="modal-card"></div>
