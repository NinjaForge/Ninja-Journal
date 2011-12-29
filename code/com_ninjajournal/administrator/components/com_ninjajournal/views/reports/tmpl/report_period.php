<? /** $Id: report_period.php 468 2010-11-20 13:10:05Z richie $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<div class="report-header">
  <h2><?= @date(array('date' => @$state->from)) ?> - <?= @date(array('date' => @$state->until)) ?></h2>
</div>
<? if(@$report['data']) : ?>
	<div class="project-report-charts" style="overflow:hidden;">
		<div style="width:100%; float:left;">
			<? $chart->renderChart() ?>
		</div>
	</div>
	
	<div class="period-report-stats">
		<table class="adminlist ninja-list">
		<thead>
			<tr>
				<th>
					<?= @text('Project') ?>
				</th>
				<th style="width:100px;">
					<?= @text('Duration') ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<? foreach (@$report['data'] as $project) : ?>
				<tr class="<?= @ninja('grid.zebra') ?>">
					<td>
						<span class="editlinktip hasTip" title="<?= @text('Report') ?>::<?= $project['name'] ?>">
							<a href="<?= @route('&project='.$project['id']) ?>"><?= $project['name'] ?></a>
						</span>
					</td>
					<td align="right">
						<?= @helper('admin::com.ninjajournal.template.helper.date.formatTimespan', array('time' => $project['duration'], 'format' => 'h:m')) ?>
					</td>
				</tr>
			<? endforeach ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" style="font-weight:bold;text-align:right;">
					<?= @helper('admin::com.ninjajournal.template.helper.date.formatTimespan', array('time' => $report['total'], 'format' => 'hr mi')) ?>
				</td>
			</tr>
		</tfoot>
		</table>
	</div>
<? else : ?>
	<?= @text('No Entries Found') ?>
<? endif ?>