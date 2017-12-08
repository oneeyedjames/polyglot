<div class="card info">
	<header><i class="fa fa-flag"></i> Translations</header>
	<ul>
		<li>
			<?php if (!get_filter('translation')) : ?><strong><?php endif; ?>
			<a href="list/<?php echo $list->id; ?>">
				<?php echo $list->language->name; ?>
			</a>
			<?php if (!get_filter('translation')) : ?></strong><?php endif; ?>
		</li>
		<?php foreach ($list->project->languages as $language) :
			if ($language->id != $list->language->id) : ?>
			<li>
				<?php if (get_filter('translation') == $language->id) : ?><strong><?php endif; ?>
				<a href="list/<?php echo $list->id; ?>/translation/<?php echo $language->id; ?>">
					<?php echo $language->name; ?>
				</a>
				<?php if (get_filter('translation') == $language->id) : ?></strong><?php endif; ?>
			</li>
		<?php endif; endforeach; ?>
	</ul>
</div>
