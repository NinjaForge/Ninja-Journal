<? /** $Id: default.php 468 2010-11-20 13:10:05Z richie $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<? if(@$length > 0) : ?>
	<table class="adminlist ninja-list">
		<thead> 
			<tr>
				<th nowrap="nowrap" style="text-align: left;">
					<?= @template('admin::com.ninja.view.search.filter_form') ?>
					<div class="button2-left" style="float:none;display:inline-block;">
						<div class="page" style="float:none;">
							<a style="float:none;" href="<?= @route('&search=&order=&direction=&state=') ?>" title="<?= @text('Reset filtering') ?>">
								<?= @text('Reset') ?>
							</a>
						</div>
					</div>
					<form action="<?= @route() ?>" method="get" style="text-align: right;float: right;" id="<?= @id('filter') ?>">
						<?= @helper('admin::com.ninjajournal.template.helper.select.state') ?>
						<input type="hidden" name="option" value="com_<?= KFactory::get($this->getView())->getIdentifier()->package ?>" />
						<input type="hidden" name="view" value="<?= KFactory::get($this->getView())->getName() ?>" />
					</form>
				</th>
			</tr>
		</thead>
	</table>
<? endif ?>

<form action="<?= @route() ?>" method="post" id="<?= @id() ?>">
	<?= @$placeholder ?>
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
				<th style="text-align: left;">
					&#160;<?= @ninja('grid.sort', array('title' => 'Type')) ?>
				</th>
				<th style="text-align: left;">
					&#160;<?= @ninja('grid.sort', array('title' => 'Project')) ?>
				</th>
				<th width="20px">
					<?= @ninja('grid.sort', array('title' => 'State')) ?>
				</th>
			</tr>
		</thead>
		<?= @ninja('paginator.tfoot', array('total' => @$total, 'colspan' => 5)) ?>
		<tbody class="ranks">
		<?= @template('default_items') ?>
		</tbody>
	</table>
</form>