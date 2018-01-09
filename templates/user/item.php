<?php $delete_nonce = $this->create_nonce('delete', 'user'); ?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="users">Users</a></li>
	<li class="active"><?php echo $user->alias; ?></li>
</ol>
<div class="row">
	<div class="col-sm-12 col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
        <h2 class="page-title">
            <i class="fa fa-user"></i> <?php echo $user->alias; ?>
            <form action="user/<?php echo $user->id; ?>/delete" method="POST" class="btn-group pull-right"
				data-confirm="Are you sure you want to delete this user?">
				<a href="users/<?php echo $user->id; ?>/form-meta" target="#modal-card"
					class="btn primary" data-action="modal" data-target="#modal-form-meta">
					<i class="fa fa-edit"></i> Edit
				</a>
				<input type="hidden" name="nonce" value="<?php echo $delete_nonce; ?>">
				<button type="submit" class="btn danger">
					<i class="fa fa-trash"></i> Delete
				</button>
			</form>
        </h2>

		<h3><i class="fa fa-newspaper-o"></i> Profile</h3>
		<div class="row">
			<p class="col-xs-12 col-sm-6">
				<label>Name</label>
				<span><?php echo $user->name; ?></span>
			</p>
			<p class="col-xs-12 col-sm-6">
				<label>Email</label>
				<span><?php echo $user->email; ?></span>
			</p>
		</div>

		<h3><i class="fa fa-lock"></i> Security</h3>
		<div class="row">
			<p class="col-xs-12 col-sm-6">
				<label>Administrator</label>
				<span>
					<?php if ($user->admin) : ?>
						<i class="fa fa-check-square-o"></i> Is Administrator
					<?php else : ?>
						<i class="fa fa-square-o"></i> Is <em>NOT</em> Administrator
					<?php endif; ?>
				</span>
			</p>
			<p class="col-xs-12 col-sm-6">
				<label>Password</label>
				<?php if ($session_user->admin || $session_user->id = $user->id) :
					if ($user->reset_token && strtotime($user->reset_expire) > time()) : ?>
					<a href="reset-password-form/token/<?php echo $user->reset_token; ?>" class="btn sm">
						<i class="fa fa-refresh"></i> Reset
					</a>
				<?php else : ?>
					<form action="reset-password" method="POST" data-confirm="Are you sure you want to reset this password?">
						<input type="hidden" name="email" value="<?php echo $user->email; ?>">
						<button type="submit" class="btn sm"><i class="fa fa-refresh"></i> Reset</button>
					</form>
				<?php endif; else : ?>
					<span><?php echo str_repeat('*', 12); ?></span>
				<?php endif; ?>
			</p>
		</div>
    </div>
    <div class="col-sm-12 col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9">
		<?php
			$this->load('item-languages', 'user', compact('user'));
			$this->load('item-projects', 'user', compact('user'));
        ?>
    </div>
</div>
