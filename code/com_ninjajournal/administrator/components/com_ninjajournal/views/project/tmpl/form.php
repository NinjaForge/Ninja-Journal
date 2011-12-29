<? /** $Id: form.php 493 2011-01-20 00:29:39Z stian $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<? @ninja('behavior.livetitle', array('title' => $project->title)) ?>

<form action="<?= @route('&id='.$project->id) ?>" method="post" id="<?= @id() ?>" class="validator-inline">
	<div class="col width-50">
		<fieldset class="adminform ninja-form">
			<legend><?= @text('Details') ?></legend>
			<div class="element">	
				<label for="title" class="key"><?= @text('Title') ?></label>
				<input type="text" name="title" id="title" class="inputbox required value" rel="<?= @text('types require a title!') ?>" size="50" value="<?= $project->title ?>" maxlength="150" />
			</div>
			<div class="element">
				<label for="state" class="key"><?= @text('State') ?></label>
				<?= @helper('admin::com.ninjajournal.template.helper.select.state', array('state' => array('state' => $project->state))) ?>
			</div>
		</fieldset>
		
		<fieldset class="adminform ninja-form">
			<legend><?= @text('Description') ?></legend>
			<div class="element" style="padding-left: 10px;">
				<?= @helper('admin::com.ninja.template.helper.editor.display') ?>
			</div>
		</fieldset>
	</div>
</form>