<div class="card">
	<header><i class="fa fa-info-circle"></i> Project Details</header>

	<h4>
		<span>Languages</span>
		<a class="pull-right btn sm blue" href="project/<?php echo $project->id; ?>/form-language"
			data-action="modal" data-target="#project-language-form">
			<i class="fa fa-edit"></i>
		</a>
	</h4>
	<ul>
		<?php foreach ($project->languages as $language) : ?>
			<li>
				<span><?php echo $language->name; ?></span>
				<small>(<?php echo $language->code; ?>)</small>
				<?php if ($language->id == $project->default_language_id) : ?>
					<span class="lbl small">Default</span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>

	<h4>
		<span>Users</span>
		<a class="pull-right btn sm green" href="project/<?php echo $project->id; ?>/form-user"
			data-action="modal" data-target="#project-user-form">
			<i class="fa fa-plus"></i>
		</a>
	</h4>
	<ul>
		<?php foreach ($project->users as $user) :
			$nonce = $this->create_nonce('remove-user', 'project'); ?>
			<li>
				<span><?php echo $user->name;?></span>
				<small>(<?php echo $user->role->title; ?>)</small>
				<div>
					<a style="text-decoration: none;">Change</a> |
					<a class="remove-user-button"
						style="text-decoration: none;"
						data-id="<?php echo $user->id; ?>">Remove</a>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
<form id="remove-user-form" action="project/<?php echo $project->id; ?>/remove-user" method="POST">
	<input type="hidden" name="user">
	<input type="hidden" name="nonce"
		value="<?php echo $this->create_nonce('remove-user', 'project'); ?>">
</form>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.remove-user-button').click(function(event) {
			event.preventDefault();

			if (confirm('Are you sure you want to remove this user?')) {
				var id = $(this).data('id');

				var form = $('#remove-user-form');

				var field = form.find('input[name="user"]');

				field.val(id);
				form.submit();
			}

			return false;
		});
	});
</script>
