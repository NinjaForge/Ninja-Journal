<? /** $Id: description.php 376 2010-08-12 21:27:46Z stian $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<? $project = KFactory::get('site::com.ninjajournal.model.projects')->id(KRequest::get('get.project', 'int'))->getItem() ?>
<div class="project-description">
	<h1><?= @$project->title ?></h1>
	<?= @$project->description ?>	
</div>