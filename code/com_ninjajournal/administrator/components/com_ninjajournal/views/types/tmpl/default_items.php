<? /** $Id: default_items.php 370 2010-08-12 13:17:28Z stian $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<? foreach ($types as $i => $type) : ?>
<tr class="<?= @ninja('grid.zebra') ?>">
	<?= @ninja('grid.count', array('total' => @$total)) ?>
	<td align="center">
		<?= @ninja('grid.id', array('value' => $type->id)) ?>
	</td>
	<td>
		&#160;<?= @$edit($type, $i, 'title', 'id') ?>
	</td>
</tr>
<? endforeach ?>
<?= @ninja('grid.placeholders', array('total' => @$total, 'colspan' => 3)) ?>