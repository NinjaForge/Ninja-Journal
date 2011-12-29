<? /** $Id: teamlog.php 468 2010-11-20 13:10:05Z richie $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<? foreach($teamlogs as $person) : ?>
	<div class="user head"><?= $person->realname ?>
		<span class="delta"> - <?= @ninja('date.beautiful', array('date' => $person->last_update)) ?></span>
	</div>
	<div class="body">
		<h2 class="state"><?= $person->message ?></h2>
		<ul class="log">
			<? foreach($person->logs as $log) : ?>
				<li>
					<a class="tooltip" title="<?= $log->project ?>::<?= $log->task ?> (<?= @helper('admin::com.ninjajournal.template.helper.date.formatTimespan', array('time' => $log->duration)) ?>)">
						<?= KFactory::get('lib.koowa.filter.string')->sanitize($log->description) ?>
					</a>
					<span class="delta"> - <?= @ninja('date.beautiful', array('date' => $log->created_on)) ?></span>
				</li>
			<? endforeach ?>
		</ul>
	</div>
<? endforeach ?>