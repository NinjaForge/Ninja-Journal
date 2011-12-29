<? /** $Id: default.php 468 2010-11-20 13:10:05Z richie $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<form action="<?= @route() ?>" method="get">
	<input type="hidden" name="option" value="com_<?= KFactory::get($this->getView())->getIdentifier()->package ?>" />
	<input type="hidden" name="view" value="<?= KFactory::get($this->getView())->getName() ?>" />
	<div id="content-controls">
		<div class="select-project">
			<?= @helper('admin::com.ninjajournal.template.helper.select.projects') ?>
		</div>
		<div class="select-user">
			<?= @helper('admin::com.ninjajournal.template.helper.select.users') ?>
		</div>
		<div class="select-date">
			<?= @$lists['select_date'] ?>
			<?= JHTML::_('calendar', @$state->from, 'from', 'from', @text('DATE_FORMAT_MYSQL_WITHOUT_TIME')) ?>
			<?= JHTML::_('calendar', @$state->until, 'until', 'until', @text('DATE_FORMAT_MYSQL_WITHOUT_TIME')) ?>
			<button onclick="this.form.submit();"><?= @text('Go') ?></button>
		</div>
	</div>
	<div id="content-area">
		<?= @template('admin::com.ninjajournal.view.reports.'.@$layout) ?>
	</div>
</form>

<? ob_start() ?>
	window.addEvent('domready', function(){
		var presets = <?= json_encode($date_presets) ?>;
		$('period').addEvent('change', function(){
			$('from').set('value', presets[this.value].from);
			$('until').set('value', presets[this.value].until);
			this.form.submit();
		});
	});
<? @$js(ob_get_clean()) ?>