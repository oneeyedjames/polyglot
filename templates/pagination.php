<?php

if (!isset($page_count))
	$page_count = intval(ceil($item_count / $per_page));

$page_limit = $page_range * 2 + 1;

if ($page < $page_range + 1)
	$page_min = 1;
elseif ($page > $page_count - $page_range)
	$page_min = $page_count - $page_limit + 1;
else
	$page_min = $page - $page_range;

$page_max = $page_min + $page_limit - 1;

if ($page_min < 1)
	$page_min = 1;

if ($page_max > $page_count)
	$page_max = $page_count;

?>
<div class="btn-group">
	<a href="<?php echo page_url($url_params, 1); ?>" class="btn"<?php disabled($page, 1); ?>><i class="fa fa-fast-backward"></i></a>
	<a href="<?php echo page_url($url_params, max($page - 1, 1)); ?>" class="btn"<?php disabled($page, 1); ?>><i class="fa fa-backward"></i></a>
	<?php if ($page_min > 1) : ?>
		</div>
		<div class="btn-group">
			<span class="btn text disabled">&hellip;</span>
		</div>
		<div class="btn-group">
	<?php endif; ?>
	<?php for ($i = $page_min; $i <= $page_max; $i++) :
		$color = $page == $i ? 'primary' : 'default';
		$disabled = $page != $i ? '' : ' disabled="disabled"'; ?>
		<a href="<?php echo page_url($url_params, $i); ?>" class="btn <?php echo $color; ?>"<?php disabled($i, $page); ?>><?php echo $i; ?></a>
	<?php endfor; ?>
	<?php if ($page_max < $page_count) : ?>
		</div>
		<div class="btn-group">
			<span class="btn text disabled">&hellip;</span>
		</div>
		<div class="btn-group">
	<?php endif; ?>
	<a href="<?php echo page_url($url_params, min($page + 1, $page_count)); ?>" class="btn"<?php disabled($page, $page_count); ?>><i class="fa fa-forward"></i></a>
	<a href="<?php echo page_url($url_params, $page_count); ?>" class="btn"<?php disabled($page, $page_count); ?>><i class="fa fa-fast-forward"></i></a>
</div>
