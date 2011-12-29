<? /** $Id: default.php 595 2011-03-27 22:09:05Z stian $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<script type="text/javascript">
window.addEvent('domready', function(){
	$('<?= @id() ?>').getElements('select').addEvent('change', function(){
		Cookie.set(this.name, this.getValue());
	});
});
</script>

<div id="ninjajournal">

	<h1 class="user">
		<?= $user->name ?>
		<a id="todos-trigger" class="todos-trigger" href="#">[<?= count($todos) ?> Todos]</a>
	</h1>

	<div class="todos">
		<div class="todos-bg">
			<div class="todos-t">
				<div class="todos-b">
					<div class="todos-l">
						<div id="todos" class="todos-line">
							<?= @template('todos') ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- todos end -->

	<div class="logs">

		<div class="left">

			<div class="log-panel">

				<form method="post" action="<?= @route() ?>" id="<?= @id() ?>">
					<div class="state">
						<textarea id="state" cols="30" rows="5" name="description"><?= $status->message ? $status->message : @text('DEFAULT_LOG') ?></textarea>
						<span class="delta"><?= @ninja('date.beautiful', array('date' => $status->on)) ?></span>
					</div>
					<div>
						<? if($log->ninjajournal_project_id) : ?>
							<? /*@TODO change to using states */ ?>
							<? KRequest::set('get.project', $log->ninjajournal_project_id) ?>
						<? endif ?>
						<select id="project-id" class="project" name="ninjajournal_project_id">
							<option class="option1" value ="">-- <?= @text('Project') ?> --</option>
							<? foreach (KFactory::get('site::com.ninjajournal.model.projects')->state('0')->getList() as $project) : ?>
								<option value ="<?= $project->id ?>"<? if($log->ninjajournal_project_id == $project->id) echo ' selected="selected"' ?>><?= $project->title ?></option>
							<? endforeach ?>
						</select>
						<span id="task-id">
							<?= @template('tasks') ?>
						</span>
					</div>
					<div>
						<label for="hours"><?= @text('Hours') ?></label>
						<select class="hours" name="duration[hours]" id="hours">
							<? foreach (range(0, 12) as $i) : ?>
								<option value ="<?= $i ?>"<? if($log->hours == $i) echo ' selected="selected"' ?>><?= $i ?></option>
							<? endforeach ?>
						</select>
						<label for="minutes"><?= @text('Minutes') ?></label>
						<select class="minutes" name="duration[minutes]" id="minutes">
							<? foreach (range(0, 55, 5) as $i) : ?>
								<option value ="<?= $i ?>"<? if($log->minutes == $i) echo ' selected="selected"' ?>><?= $i ?></option>
							<? endforeach ?>
						</select>
						<span class="ninja-button-wrap"><button type="submit" class="ninja-button"><?= @text('Add to log') ?></button></span>
					</div>
				</form>

				<div id="project-description">
					<? if($log->ninjajournal_project_id) : ?>
						<?= @template('description') ?>
					<? endif ?>
				</div>

			</div>

			<div class="user-log">
				<div id="logs">
					<?= @template('default_logs') ?>
				</div>
			</div>

		</div>

		<div class="right">

			<? if (count($teamlogs)) : ?>
				<div class="team-log">
					<div class="team-log-t">
						<div class="team-log-b">
							<div class="team-log-l">
								<div class="team-log-r">
									<div class="team-log-tl">
										<div class="team-log-tr">
											<div class="team-log-bl">
												<div class="team-log-br">
													<div class="team-log-loading">
														<div class="team-log-line">
															<div id="team-log" class="team-log-hole">
																<?= @template('teamlog') ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<? endif ?>
			<!-- team-log end -->

		</div>
		<!-- right-log end -->

	</div>
	<!-- log-container end -->

</div>

<script type="text/javascript">
	window.addEvent('domready', function(){
		var app = new Journal('<?= @route('&view=log&format=raw', true) ?>', <?= json_encode(array('token' => JUtility::getToken(), 'msgDeletelog' => @text('Are you sure you want to delete the log?') )) ?>);
		app.attachEvents();
		
		$('logs').addEvent('click', function(event){
			var event = new Event(event), target = $(event.target);
			if(target.getProperty('id') != 'load-more-logs' || !target.href) return;
			target.getParent().addClass('active');
			new Ajax(target.href.split('#')[0]+'&tmpl=&format=raw', {
				method: 'get',
				update: $('logs'),
				onSuccess: function(){
					app.refresh();
					new Fx.Scroll(window).toElement($('load-more-logs'));
					new Tips($$('ul.log a.tooltip'), {fixed: true, className: 'ninjatool', offsets: {'x': 0, 'y': 28}});
				},
				onFailure: function(response){
					target.getParent().removeClass('active');
					try {
						console.error('Failed to load more logs.');
					}
					catch(E) {
						alert('Failed to load more logs.');	
					}
				}
			}).request();

			event.stop();
		});
		
		//new Accordion($$('#team-log .head'), $$('#team-log .body'), {duration: 300,opacity: true}); 
	});
</script>