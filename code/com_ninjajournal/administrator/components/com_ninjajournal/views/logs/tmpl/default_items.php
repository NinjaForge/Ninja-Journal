<? /** $Id: default_items.php 375 2010-08-12 20:53:13Z stian $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<? $states = array(0 => 'open', 1 => 'done', 2 => 'close') ?>

<? foreach ($logs as $i => $log) : ?>
<tr class="<?= @ninja('grid.zebra') ?> state-<?= @$states[$log->state] ?>">
	<?= @ninja('grid.count', array('total' => @$total)) ?>
	<td align="center">
		<?= @ninja('grid.id', array('value' => $log->id)) ?>
	</td>
	<td>
		<?= @$edit($log, $i, 'description', 'id', false) ?>
	</td>
	<td nowrap="nowrap">
		&#160;<?= $log->username ?>&#160;
	</td>
	<td nowrap="nowrap">
		&#160;<?= $log->project ?>&#160;
	</td>
	<td nowrap="nowrap">
		&#160;<?= $log->task ?>&#160;
	</td>
	<td nowrap="nowrap">
		&#160;<?= $log->duration ?>&#160;
	</td>
	<td nowrap="nowrap">
		&#160;<?= @ninja('date.beautiful', array('date' => $log->created_on)) ?>&#160;
	</td>
</tr>
<? endforeach ?>
<?= @ninja('grid.placeholders', array('total' => @$total, 'colspan' => 8)) ?>