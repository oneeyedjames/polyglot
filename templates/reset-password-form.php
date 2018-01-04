<div class="row">
	<div class="col-sm-2 col-md-3 col-lg-4">&nbsp;</div>
	<div class="col-sm-8 col-md-6 col-lg-4">
		<blockquote>
			<p>And how <em>is it that</em> we hear, each in our own language in which we were born?</p>
			<footer>Acts 2:8 <cite>NKJV</cite></footer>
		</blockquote>
		<?php if (isset($error)) : ?>
			<div class="card alert error"><?php echo $error; ?></div>
		<?php elseif (isset($user)) : ?>
			<div class="card primary">
				<form action="reset-password" method="POST">
					<header>Reset Password</header>

					<input type="hidden" name="token" value="<?php echo $token; ?>">

					<label for="login-password">New Password</label>
					<input id="login-password" type="password"
						name="login[password]" placeholder="Password">

					<label for="login-password-confirm">Confirm Password</label>
					<input id="login-password-confirm" type="password"
						name="login[password-confirm]" placeholder="Confirm Password">

					<footer>
						<button type="submit" class="btn primary">
							<i class="fa fa-refresh"></i> Reset
						</button>
					</footer>
				</form>
			</div>
		<?php endif;?>
	</div>
	<div class="col-sm-2 col-md-3 col-lg-4">&nbsp;</div>
</div>
