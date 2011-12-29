<? /** $Id: form.php 493 2011-01-20 00:29:39Z stian $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<form action="<?= @route('&id='.@$log->id) ?>" method="post" id="<?= @id() ?>" class="validator-inline">
	<div class="col width-50">
		<fieldset class="adminform ninja-form">
			<legend><?= @text('Details') ?></legend>
			<? if (@$log->id) : ?>
				<div class="element">	
					<?= @ninja('behavior.autocomplete', array(
						'value' => $log->user_id, 
						'name' => 'user_id',
						'model' => @route('view=users&format=json', true),
						'label' => 'User',
						'text' => KFactory::tmp('admin::com.ninjajournal.model.users')->id($log->user_id)->getItem()->text,
						'placeholder' => 'Start typing a username'
					)) ?>
				</div>
			<? endif ?>
			<div class="element">
				<label for="project" class="key"><?= @text('Project'); ?></label>
				<?= @helper('admin::com.ninjajournal.template.helper.select.projects', array('state' => array('project' => $log->ninjajournal_project_id))) ?>
			</div>
			<div class="element">
				<label for="task" class="key"><?= @text('Task') ?></label>
				<script type="text/javascript">
					window.addEvent('domready', function(){
						var url = '<?= str_replace('&amp;', '&', @route('&layout=tasks&format=raw')) ?>', tasks = $('<?= @id('tasks') ?>'), task;
						$('project').addEvent('change', function(){
							task = $('task').get('value');
							tasks.set('load', {
								url: url,
								data: {
									project: this.get('value')
								},
								onSuccess: function(){
									$('task').set('value', task);
								}
							}).load();
						});
					});
				</script>
				<? if($log->ninjajournal_project_id) KRequest::set('get.project', @$log->ninjajournal_project_id) ?>
				<span id="<?= @id('tasks') ?>"><?= @template('tasks') ?></span>
			</div>
			<? if (!@$log->id) : ?>
				<div class="element">
					<label class="key" for="hours"><?= @text('Hours') ?></label>
					<select class="hours value" name="duration[hours]" id="hours">
						<?php for ($i=0; $i <= 12; $i++) : ?>
							<option value ="<?= $i ?>"><?= $i ?></option>
						<?php endfor; ?>
					</select>
				</div>
				<div class="element">
					<label class="key" for="minutes"><?= @text('Minutes') ?></label>
					<select class="minutes value" name="duration[minutes]" id="minutes">
						<?php for ($i=0; $i < 60; $i+=5) : ?>
							<option value ="<?= $i ?>"><?= $i ?></option>
						<?php endfor; ?>
					</select>
				</div>
			<? else : ?>
				<div class="element">
					<label class="key" for="duration"><?= @text('Duration') ?></label>
					<input type="text" value="<?= $log->duration ?>" class="duration value" name="duration" id="duration" />
				</div>
			<? endif ?>
			<? if (@$log->created_on > 0) : ?>
				<div class="element">
					<label class="key"><?= @text('Created') ?></label>
					<?= @ninja('date.beautiful', array('date' => $log->created_on)) ?>
				</div>
			<? endif ?>
			<? if (@$log->modified_on > 0) : ?>
				<div class="element">
					<label class="key"><?= @text('Modified') ?></label>
					<?= @ninja('date.beautiful', array('date' => $log->modified_on)) ?>
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