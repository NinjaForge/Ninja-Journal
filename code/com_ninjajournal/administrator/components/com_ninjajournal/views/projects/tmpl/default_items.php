<? /** $Id: default_items.php 468 2010-11-20 13:10:05Z richie $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<? foreach ($projects as $i => $project) : ?>
<tr class="<?= @ninja('grid.zebra') ?> state-<?= @toggle($project->state, 'close', 'open') ?>">
	<?= @ninja('grid.count', array('total' => @$total)) ?>
	<td align="center">
		<?= @ninja('grid.id', array('value' => $project->id)) ?>
	</td>
	<td>
		&#160;<?= @$edit($project, $i, 'title', 'id') ?>
	</td>
	<td>
		<?= @helper('admin::com.ninjajournal.template.helper.select.state', array('state' => $project->state)) ?>
	</td>
</tr>
<? endforeach ?>
<?= @ninja('grid.placeholders', array('total' => @$total, 'colspan' => 4)) ?>