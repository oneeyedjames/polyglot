<?php

if (isset($language->id))
	$url = "language/$language->id";
else
	$url = 'languages';

$url .= '/save';

$nonce = $this->create_nonce('save', 'language');

?>
<form action="<?php echo $url; ?>" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<div class="modal card primary" id="modal-form-language">
		<header>
			<i class="fa fa-flag"></i> Edit Language
			<a class="pull-right cancel"><i class="fa fa-close"></i></a>
		</header>

		<label>Language</label>
		<input type="text" name="language[name]" value="<?php echo $language->name; ?>">

		<label>Code</label>
		<input type="text" name="language[code]" value="<?php echo $language->code; ?>">

		<footer class="btns">
			<button type="button" class="btn cancel">Cancel</button>
			<button type="submit" class="btn primary">Save</button>
		</footer>
	</div>
</form>
