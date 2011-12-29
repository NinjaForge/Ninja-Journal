<? /** $Id: default.php 375 2010-08-12 20:53:13Z stian $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<?= @template('admin::com.ninja.view.search.filter_thead') ?>

<form action="<?= @route() ?>" method="post" id="<?= @id() ?>">
	<?= @$placeholder() ?>
	<table class="adminlist ninja-list">
		<thead>
			<tr>
				<?= @ninja('grid.count', array('total' => @$total, 'title' => true)) ?>
				<th width="20px">
					<?= @ninja('grid.checkall') ?>
				</th>
				<th style="text-align: left;">
					&#160;<?= @ninja('grid.sort', array('title' => 'Title')) ?>
				</th>				
			</tr>
		</thead>
		<?= @ninja('paginator.tfoot', array('total' => @$total, 'colspan' => 3)) ?>
		<tbody>
			<?= @template('default_items') ?>
		</tbody>
	</table>
</form>