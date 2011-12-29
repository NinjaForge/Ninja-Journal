<? /** $Id: form.php 493 2011-01-20 00:29:39Z stian $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<form action="<?= @route('&id='.$todo->id) ?>" method="post" id="<?= @id() ?>" class="validator-inline">
	<div class="col width-50">
		<fieldset class="adminform ninja-form">
			<legend><?= @text('Details') ?></legend>
			<div class="element">	
				<?= @ninja('behavior.autocomplete', array(
					'value' => $todo->user_id, 
					'name' => 'user_id',
					'model' => @route('view=users&format=json', true),
					'label' => 'Assign to',
					'text' => KFactory::tmp('admin::com.ninjajournal.model.users')->id($todo->user_id)->getItem()->text,
					'placeholder' => 'Start typing a username'
				)) ?>
			</div>
			<div class="element">
				<label for="state" class="key"><?= @text('State') ?></label>
				<?= @helper('admin::com.ninjajournal.template.helper.select.state', array('state' => array('state' => @$todo->state))) ?>
			</div>
			<? if (@$todo->created_on > 0) : ?>
				<div class="element">
					<label class="key"><?= @text('Created') ?></label>
					<?= @ninja('date.beautiful', array('date' => $todo->created_on)) ?>
				</div>
			<? endif ?>
			<? if (@$todo->modified_on > 0) : ?>
				<div class="element">
					<label class="key"><?= @text('Modified') ?></label>
					<?= @ninja('date.beautiful', array('date' => @$todo->modified_on)) ?>
				</div>
			<? endif ?>
		</fieldset>
		
		<fieldset class="adminform ninja-form">
			<legend><?= @text('Description') ?></legend>
			<div class="element" style="padding-left: 10px;">
				<? @$js("window.addEvent('domready', function(){
					$('description').addClass('required');
				});") ?>
				<?= @helper('admin::com.ninja.template.helper.editor.display') ?>
			</div>
		</fieldset>
	</div>
</form>