<? /** $Id: default_logs.php 468 2010-11-20 13:10:05Z richie $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<? foreach (@$logs as $date => $logs) : ?>
	<span class="date">
		<?= $date ?>
	</span>
	<ul class="log">
		<? foreach ($logs as $log) : ?>
			<li id="<?= @id($log->id) ?>">
				<a class="tooltip" title="<?= $log->project.' :: '.$log->task.' ('. @helper('admin::com.ninjajournal.template.helper.date.formatTimespan', array('time' => $log->duration)) .')' ?>"><?= KFactory::get('lib.koowa.filter.string')->sanitize($log->description) ?></a>
				<? if ( $date === @text('Today') ) : ?>
					 <span class="delta">
						- <?= @ninja('date.beautiful', array('date' => $log->created_on)) ?>
						<a href="#<?= $log->id ?>" id="<?= @id($log->id.'-delete') ?>"><?= @text('Delete') ?></a>
					</span>
				<? endif ?>

			</li>
		<? endforeach ?>
	</ul>
<? endforeach ?>

<? $limit = (@$state->limit + 10) ?>
<? if ( $limit > @$total + 10 ) $limit = @$total ?>
<span class="ninja-button-wrap light<? if($limit === @$total) echo ' inactive' ?>">
	<a name="load-more" <? if($limit !== @$total) : ?>href="<?= @route('&limit=' . $limit ) ?>#load-more" <? endif ?>id="load-more-logs" class="ninja-button light">
		<?= @text('More') ?>
	</a>
</span>