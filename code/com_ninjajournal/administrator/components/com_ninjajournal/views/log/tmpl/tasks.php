<? /** $Id: tasks.php 209 2010-03-10 22:09:10Z stian $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<select class="task value required" name="ninjajournal_task_id" id="task">
	<option class="option1" value ="">- <?= @text('Select Task') ?> -</option>
	<? if(KRequest::has('get.project', 'int')) : ?>
		
		<? $project = KRequest::get('get.project', 'int') ?>
		
		<? foreach (KFactory::get('admin::com.ninjajournal.model.types')->getList() as $type) : ?>
			<? $model = KFactory::tmp('admin::com.ninjajournal.model.tasks')->project($project)->type($type->id) ?>
			<?= $model->getTotal() ?>
			<? if($model->getTotal() < 1) continue ?>
			<optgroup class="option2" label="<?php echo $type->title ?>">
			<? foreach ($model->getList() as $task) : ?>
				<option value ="<?= $task->id ?>"<? if($task->id == @$log->ninjajournal_task_id) echo ' selected="selected"' ?>><?= $task->title ?></option>
			<? endforeach ?>
			</optgroup>
		<? endforeach ?>
	<? endif ?>
</select>