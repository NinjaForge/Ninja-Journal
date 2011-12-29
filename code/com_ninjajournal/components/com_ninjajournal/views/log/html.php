<?php
/**
 * @version		$Id: html.php 592 2011-03-27 21:45:22Z stian $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

KLoader::load('admin::com.ninja.view.default');

class ComNinjajournalViewLogHtml extends ComNinjaViewDefault
{
	/**
	 * Disables the toolbar autoloading in napi
	 *
	 * @var boolean
	 */
	protected $_toolbar = false;

	public function display()
	{
		$this->user = KFactory::get('lib.joomla.user');
	
		if(!$this->user->id)
		{
			$this->mixin(KFactory::get('admin::com.ninja.view.user.mixin'));
			
			$this->setLoginLayout();
			
			return parent::display();
		}
	
		$app = KFactory::get('lib.koowa.application');
		$tpl = strtolower(substr($app->getTemplate(), 0, 3));
		KFactory::get('admin::com.ninja.helper.default')->css('/default.css');
		if ($tpl != 'yoo') KFactory::get('admin::com.ninja.helper.default')->css('/reset.css');
		KFactory::get('admin::com.ninja.helper.default')->js('/log.js');
		
		$this->status = KFactory::get('site::com.ninjajournal.model.status')->by($this->user->id)->getItem();
		
		$this->todos  = KFactory::get('site::com.ninjajournal.model.todos')
							->user($this->user->id)
							->closed(false)
							->sort('created_on')
							->direction('desc')
							->getList();

		$model = KFactory::get($this->getModel());
		$limit = $model->getState()->limit;
		$this->{KInflector::pluralize($this->getName())} = $model
															->user($this->user->id)
															->sort('created_on')
															->direction('desc')
															->limit($limit ? $limit : 10)
															->getGroupedList();
															
		$this->total	= $model->getTotal();
		$this->teamlogs = $model->getTeamlogs();

		return parent::display();
	}
}