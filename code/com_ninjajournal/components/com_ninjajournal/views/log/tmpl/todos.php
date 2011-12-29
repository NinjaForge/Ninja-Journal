<? /** $Id: todos.php 444 2010-09-28 18:42:37Z stian $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<form id="todos-form" method="post" action="<?= @route('&view=todos') ?>">
	<? if (count($todos)) : ?>
		<? @js('/toggle.js') ?>
		<ul>
		<? foreach ($todos as $todo) : ?>
			<li<? if($todo->state) echo ' class="state-state-true"' ?>>
				<div style="display: none;"><?= @ninja('grid.id', array('value' => $todo->id, 'label' => false, 'checked' => $todo->state)) ?></div>
				
				<div class="checkbox"><?= @ninja('grid.toggle', array('toggle' => 'state', 'state' => (int)$todo->state, 'id' => $todo->id/*, array('done', 'open')*/)) ?></div>
				<div class="todo"><?= $todo->description ?></div>
			</li>
		<? endforeach ?>
		</ul>
	<? else : ?>
		<?= @text('No todos assigned') ?>
	<? endif ?>
</form>