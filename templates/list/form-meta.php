<?php

if (isset($list->id))
	$url = "list/$list->id";
elseif (isset($list->project))
	$url = "project/{$list->project->id}/lists";

$url .= '/save';

$nonce = $this->create_nonce('save', 'list');

?>
<form action="<?php echo $url; ?>" method="POST">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
	<div class="modal card blue" id="modal-form-list">
		<header>Edit Term List</header>

		<label>Title</label>
		<input type="text" name="list[title]" value="<?php echo $list->title; ?>">

		<label>Description</label>
		<textarea name="list[description]" rows="2"><?php echo $list->descrip; ?></textarea>

		<footer>
			<button type="button" class="btn cancel">Cancel</button>
			<button type="submit" class="btn blue">Save</button>
		</footer>
	</div>
</form>
