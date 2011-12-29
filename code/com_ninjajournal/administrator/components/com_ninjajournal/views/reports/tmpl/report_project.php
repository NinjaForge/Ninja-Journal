<? /** $Id: report_project.php 468 2010-11-20 13:10:05Z richie $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<div class="report-header">
  <h2><?= (@$state->from && @$state->until) ? @date(array('date' => @$state->from)) .' - '. @date(array('date' => @$state->until)) : @text('No Period Specified') ?>
  </h2>
</div>
<? if(@$report['data']) : ?>

	<div class="project-report-charts" style="overflow:hidden;">
		<? foreach ($charts as $chart) : ?>
			<div style="width:<?= 100 / count($charts) ?>%; float: left;">
				<? $chart->renderChart() ?>
			</div>
		<? endforeach ?>
	</div>
	
	<div class="project-report-stats">
		<table class="adminlist ninja-list">
		<thead>
			<tr>
				<th><?= @text('Type') ?></th>
				<th><?= @text('Task') ?></th>
				<th><?= @text('Log') ?></th>
				<th><?= @text('User') ?></th>
				<th><?= @text('Date') ?></th>
				<th style="width:100px;"><?= @text('Duration') ?></th>
			</tr>
		</thead>
		<tbody>
			<? $filter = KFactory::get('lib.koowa.filter.string') ?>
			<? foreach (@$report['type'] as $type) : ?>
				<? $type_isset = true ?>
				<? foreach ($type['task'] as $task) : ?>
					<? $task_isset = true ?>
					<? foreach ($task['log'] as $log_id) : ?>
						<? $log = @$report['data'][$log_id] ?>
						<tr class="<?= @ninja('grid.zebra') ?>">
							<td>
							<? if ($type_isset) : ?>
								<?= $type['name'] ?>
								<? $type_isset = false ?>
							<? endif ?>
							</td>
							<td>
							<? if ($task_isset) : ?>
								<?= $task['name'] ?>
								<? $task_isset = false ?>
							<? endif ?>
							</td>
							<td><?= $filter->sanitize($log['log']) ?></td>
							<td><?= $log['username'] ?></td>
							<td><?= @date(array('date' => $log['date'])) ?></td>
							<td align="right"><?= @helper('admin::com.ninjajournal.template.helper.date.formatTimespan', array('time' => $log['duration'], 'format' => 'h:m')) ?></td>
						</tr>
					<? endforeach ?>
				<? endforeach ?>
			<? endforeach ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="6" style="font-weight:bold;text-align:right;">
					<?= @helper('admin::com.ninjajournal.template.helper.date.formatTimespan', array('time' => $report['total'], 'format' => 'hr mi')) ?>
				</td>
			</tr>			
		</tfoot>
		</table>
	</div>

<? else : ?>
	<?= @text('No Entries Found') ?>
<? endif ?>