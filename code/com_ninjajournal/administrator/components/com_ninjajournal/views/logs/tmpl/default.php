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
							<a style="float:none;" href="<?= @route('&search=&order=created_on&direction=desc&state=&user=&project=') ?>" title="<?= @text('Reset filtering') ?>">
								<?= @text('Reset') ?>
							</a>
						</div>
					</div>
					<form action="<?= @route() ?>" method="get" style="text-align: right;float: right; position: relative; top: 2px; right: 2px;" id="<?= @id('filter') ?>">
						<?= @helper('admin::com.ninjajournal.template.helper.select.users') ?>
						<?= @helper('admin::com.ninjajournal.template.helper.select.projects') ?>
						<input type="hidden" name="option" value="com_<?= KFactory::get($this->getView())->getIdentifier()->package ?>" />
						<input type="hidden" name="view" value="<?= KFactory::get($this->getView())->getName() ?>" />
					</form>
				</th>
			</tr>
		</thead>
	</table>
<? endif ?>

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
					&#160;<?= @ninja('grid.sort', array('title' => 'Description')) ?>
				</th>
				<th style="text-align: left;">
					&#160;<?= @ninja('grid.sort', array('title' => 'User', 'order' => 'user_id')) ?>
				</th>
				<th style="text-align: left;">
					&#160;<?= @ninja('grid.sort', array('title' => 'Project')) ?>
				</th>
				<th width="20px" style="text-align: left;">
					<?= @ninja('grid.sort', array('title' => 'Task')) ?>
				</th>
				<th width="20px" style="text-align: left;">
					<?= @ninja('grid.sort', array('title' => 'Duration')) ?>
				</th>
				<th width="20px">
					<?= @ninja('grid.sort', array('title' => 'Created', 'order' => 'created_on')) ?>
				</th>
			</tr>
		</thead>
		<?= @ninja('paginator.tfoot', array('total' => @$total, 'colspan' => 8)) ?>
		<tbody class="ranks">
		<?= @template('default_items') ?>
		</tbody>
	</table>
</form>