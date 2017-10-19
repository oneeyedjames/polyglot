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
	<header>Edit Language</header>

	<label>Language</label>
	<input type="text" name="language[name]" value="<?php echo $language->name; ?>">

	<label>Code</label>
	<input type="text" name="language[code]" value="<?php echo $language->code; ?>">

	<footer>
		<button type="submit" class="btn blue">Save</button>
		<button type="button" class="btn cancel">Cancel</button>
	</footer>
</form>
