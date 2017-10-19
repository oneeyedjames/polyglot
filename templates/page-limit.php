<div class="btn-group">
	<?php for ($i = 0; $i < 3; $i++) : $l = 12 * pow(2, $i);
		$color = $per_page == $l ? 'blue' : 'default';
		$disabled = $per_page != $l ? '' : ' disabled="disabled"'; ?>
		<a href="<?php echo per_page_url($url_params, $l); ?>" class="btn <?php echo $color; ?>"<?php echo $disabled; ?>><?php echo $l; ?></a>
	<?php endfor; ?>
</div>
