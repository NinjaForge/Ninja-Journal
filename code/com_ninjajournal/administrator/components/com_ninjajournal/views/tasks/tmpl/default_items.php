<? /** $Id: default_items.php 468 2010-11-20 13:10:05Z richie $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<? foreach ($tasks as $i => $task) : ?>
<tr class="<?= @ninja('grid.zebra') ?> state-<?= @toggle($task->state, 'close', 'open') ?>">
	<?= @ninja('grid.count', array('total' => @$total)) ?>
	<td align="center">
		<?= @ninja('grid.id', array('value' => $task->id)) ?>
	</td>
	<td>
		&#160;<?= @$edit($task, $i, 'title', 'id') ?>
	</td>
	<td>
		&#160;<?= $task->type ?>
	</td>
	<td>
		&#160;<?= $task->project ? $task->project : JText::_('Global (All Projects)') ?>
	</td>
	<td>
		<?= @helper('admin::com.ninjajournal.template.helper.select.state', array('state' => array('state' => $task->state))) ?>
	</td>
</tr>
<? endforeach ?>
<?= @ninja('grid.placeholders', array('total' => @$total, 'colspan' => 6)) ?>