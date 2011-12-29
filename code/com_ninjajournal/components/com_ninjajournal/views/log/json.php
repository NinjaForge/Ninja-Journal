<?php
/**
 * @version		$Id: json.php 592 2011-03-27 21:45:22Z stian $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

KLoader::load('admin::com.ninja.view.json');

class ComNinjajournalViewLogJson extends ComNinjaViewJson
{

	public function display()
	{
		$this->user = KFactory::get('lib.koowa.user');
	
		KFactory::get('lib.koowa.document')->setMimeEncoding('text/html');
	
		if(!$this->user->id)
		{
			$this->mixin(KFactory::get('admin::com.ninja.view.user.mixin'));
			
			$this->setLoginLayout();
			return;
			return parent::display();
		}
	
		$app = KFactory::get('lib.koowa.application');
		$tpl = strtolower(substr($app->getTemplate(), 0, 3));
		
		$this->status = KFactory::get('site::com.ninjajournal.model.status')->by($this->user->id)->getItem();
		
		$this->todos  = KFactory::get('site::com.ninjajournal.model.todos')->user($this->user->id)->closed(false)->getList();
		
		$model = KFactory::get($this->getModel());
		$this->{KInflector::pluralize($this->getName())} = $model
															->user($this->user->id)
															->sort('created_on')
															->direction('desc')
															->getList();
		$this->teamlogs = $model->getTeamlogs();

		echo json_encode($this);
	}
}