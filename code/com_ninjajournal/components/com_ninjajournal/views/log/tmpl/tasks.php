<? /** $Id: tasks.php 444 2010-09-28 18:42:37Z stian $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<select class="task" name="ninjajournal_task_id">
	<option class="option1" value ="">-- <?= @text('Task') ?> --</option>
	<? if (KRequest::has('get.project', 'int')) : ?>
		
		<? $project = KRequest::get('get.project', 'int') ?>
		
		<? foreach (KFactory::get('site::com.ninjajournal.model.types')->getList() as $type) : ?>
			<? $model = KFactory::tmp('site::com.ninjajournal.model.tasks')->state('0')->project($project)->type($type->id) ?>
			<?= $model->getTotal() ?>
			<? if($model->getTotal() < 1) continue ?>
			<optgroup class="option2" label="<?= $type->title ?>">
			<? foreach ($model->getList() as $task) : ?>
				<option value ="<?= $task->id ?>"<? if($log->ninjajournal_task_id == $task->id) echo ' selected="selected"' ?>><?= $task->title ?></option>
			<? endforeach ?>
			</optgroup>
		<? endforeach ?>
	<? endif ?>
</select>