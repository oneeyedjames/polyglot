<a href="<?php echo sort_url($url_params, $key, $order); ?>">
	<?php echo $title; ?>
	<?php if ($dir) : ?>
		<i class="fa fa-caret-<?php echo $dir; ?>"></i>
	<?php endif; ?>
</a>
