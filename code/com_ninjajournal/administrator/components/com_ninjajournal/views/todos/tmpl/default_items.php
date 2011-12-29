<? /** $Id: default_items.php 468 2010-11-20 13:10:05Z richie $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<? $states = array(0 => 'open', 1 => 'done', 2 => 'close') ?>

<? foreach ($todos as $i => $todo) : ?>
<tr class="<?= @ninja('grid.zebra') ?> state-<?= @$states[$todo->state] ?>">
	<?= @ninja('grid.count', array('total' => @$total)) ?>
	<td align="center">
		<?= @ninja('grid.id', array('value' => $todo->id)) ?>
	</td>
	<td>
		<?= @$edit($todo, $i, 'description', 'id', false) ?>
	</td>
	<td nowrap="nowrap">
		&#160;<?= $todo->username ?>&#160;
	</td>
	<td nowrap="nowrap">
		&#160;<?= @ninja('date.beautiful', array('date' => $todo->created_on)) ?>&#160;
	</td>
	<td nowrap="nowrap">
		&#160;
		<?= $todo->modified_on > 0 
			? @ninja('date.beautiful', array('date' => $todo->modified_on)) 
			: @text('Unmodified') 
		?>
		&#160;
	</td>
	<td>
		<?= @helper('admin::com.ninjajournal.template.helper.select.state', array('state' => $todo->state)) ?>
	</td>
</tr>
<? endforeach ?>
<?= @ninja('grid.placeholders', array('total' => @$total, 'colspan' => 7)) ?>