/* Add custom scripts here */

jQuery(document).ready(function($) {
	$('[data-action=toggle]').click(function(e) {
		e.preventDefault();

		var button = $(this);
		var target = $(button.data('target'));

		target.toggle();
		target.find('ul.nav ul').toggleClass('active');
	});

	$('form[data-warning]').submit(function(e) {
		var warning = $(this).data('warning');
		if (warning && !confirm(warning))
			e.preventDefault();
	});
});
