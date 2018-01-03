<?php $delete_nonce = $this->create_nonce('delete', 'user'); ?>
<ol class="breadcrumb">
	<li><a href="home"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="users">Users</a></li>
	<li class="active"><?php echo $user->name; ?></li>
</ol>
<div class="row">
	<div class="col-sm-12 col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
        <h2 class="page-title">
            <i class="fa fa-user"></i> <?php echo $user->name; ?>
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
				<label>Email Address</label>
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
				<?php if (empty($user->reset_token) || strtotime($user->reset_expire) < time()) : ?>
					<i class="fa fa-refresh"></i> Reset
				<?php else : ?>
					<i class="fa fa-ban"></i> Cancel
				<?php endif; ?>
			</p>
		</div>
		<?php if (!empty($user->reset_token) && strtotime($user->reset_expire) >= time()) : ?>
			<div class="row">
				<p class="col-xs-12 col-sm-6">
					<label>Password Reset Token</label>
					<span><?php echo $user->reset_token; ?></span>
					<a class="btn text danger"><i class="fa fa-ban"></i> Cancel</a>
				</p>
				<p class="col-xs-12 col-sm-6">
					<label>Token Expiration</label>
					<span><?php echo $user->reset_expire; ?></span>
				</p>
			</div>
		<?php endif; ?>
    </div>
    <div class="col-sm-12 col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9">
		<?php
			$this->load('item-languages', 'user', compact('user'));
			$this->load('item-projects', 'user', compact('user'));
        ?>
    </div>
</div>
