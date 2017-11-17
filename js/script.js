/* Add custom scripts here */

jQuery(document).ready(function($) {
	$('[data-action=toggle]').click(function(e) {
		e.preventDefault();

		var button = $(this);
		var target = $(button.data('target'));

		target.toggle();
		target.find('ul.nav ul').toggleClass('active');
	});
});
