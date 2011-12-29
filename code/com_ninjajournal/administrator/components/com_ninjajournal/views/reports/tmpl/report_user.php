<? /** $Id: report_user.php 468 2010-11-20 13:10:05Z richie $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<div class="report-header">
  <h2><?= (@$state->from && @$state->until) ? @date(array('date' => @$state->from)) . ' - ' . @date(array('date' => @$state->until)) : JText::_('No Period Specified') ?>
  </h2>
</div>

<? if (@$report['data']) : ?>

	<div class="project-report-charts" style="overflow:hidden;">
		<div style="width:100%; float:left;">
			<? @$chart->renderChart() ?>
		</div>
	</div>
	
	<div class="user-report-stats">
		<? foreach (@$report['data'] as $week_logs) : ?>
			<div class="user-report-week">
				<h3><?= $week_logs['title'] ?></h3>
				<table class="adminlist ninja-list">
					<thead>
						<tr>
							<th style="width:200px;"><?= @text('Project') ?></th>
							<th style="width:200px;"><?= @text('Task') ?></th>
							<th><?= @text('Log') ?></th>
							<th style="width:200px;"><?= @text('Date') ?></th>
							<th style="width:100px;"><?= @text('Duration') ?></th>
						</tr>
					</thead>
					<tbody>
						<? $filter = KFactory::get('lib.koowa.filter.string') ?>
						<? foreach ($week_logs['logs'] as $log) : ?>
							<tr class="<?= @ninja('grid.zebra') ?>">
								<td><?= $log['project_name'] ?></td>
								<td><?= $log['task_name'] ?></td>
								<td><?= $filter->sanitize($log['log']) ?></td>
								<td><?= @date(array('date' => $log['date'])) ?></td>
								<td align="right">
									<?= @helper('admin::com.ninjajournal.template.helper.date.formatTimespan', array('time' => $log['duration'], 'format' => 'h:m')) ?>
								</td>
							</tr>
						<? endforeach ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5" style="font-weight:bold;text-align:right;">
								<?= @helper('admin::com.ninjajournal.template.helper.date.formatTimespan', array('time' => $week_logs['total'], 'format' => 'hr mi')) ?>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		<? endforeach ?>
	</div>

<? else : ?>
	<?= @text('No Entries Found') ?>
<? endif ?>