<? /** $Id: form.php 493 2011-01-20 00:29:39Z stian $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<? @ninja('behavior.livetitle', array('title' => $task->title)) ?>

<form action="<?= @route('&id='.$task->id) ?>" method="post" id="<?= @id() ?>" class="validator-inline">
	<div class="col width-50">
		<fieldset class="adminform ninja-form">
			<legend><?= @text('Details') ?></legend>
			<div class="element">	
				<label for="title" class="key"><?= @text('Title') ?></label>
				<input type="text" name="title" id="title" class="inputbox required value" rel="<?= @text('types require a title!') ?>" size="50" value="<?= $task->title ?>" maxlength="150" />
			</div>
			<div class="element">
				<label for="state" class="key"><?= @text('State') ?></label>
				<?= @helper('admin::com.ninjajournal.template.helper.select.state', array('state' => array('state' => $task->state))) ?>
			</div>
			<div class="element">
				<label for="project" class="key"><?= @text('Project'); ?></label>
				<?=@helper('admin::com.ninjajournal.template.helper.select.projects', array('state' => array('project' => $task->ninjajournal_project_id))) ?>
			</div>
			<div class="element">
				<label for="type" class="key"><?= @text('Type'); ?></label>
				<?=@helper('admin::com.ninjajournal.template.helper.select.types', array('state' => array('type' => $task->ninjajournal_type_id))) ?>
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