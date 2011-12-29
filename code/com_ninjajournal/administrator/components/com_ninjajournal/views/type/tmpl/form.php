<? /** $Id: form.php 292 2010-07-01 00:41:53Z stian $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<? @ninja('behavior.livetitle', array('title' => $type->title)) ?>

<form action="<?= @route('&id='.$type->id) ?>" method="post" id="<?= @id() ?>" class="validator-inline">
	<div class="col width-50">
		<fieldset class="adminform ninja-form">
			<legend><?= @text('Details') ?></legend>
			<div class="element">	
				<label for="title" class="key"><?= @text('Title') ?></label>
				<input type="text" name="title" id="title" class="inputbox required value" rel="<?= @text('types require a title!') ?>" size="50" value="<?= $type->title ?>" maxlength="150" />
			</div>
		</fieldset>
	</div>
</form>