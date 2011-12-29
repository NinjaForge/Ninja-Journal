<?php
/**
 * @version		$Id: log.php 597 2011-03-27 22:10:17Z stian $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

/**
 * NinjaJournal Log Controller
 *
 * @package NinjaJournal
 */
class ComNinjajournalControllerLog extends ComNinjajournalControllerDefault
{
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		//Set the layout, depending on the request
		$layout = KRequest::type() == 'AJAX' ? 'default_logs' : 'default';
		$config->request->append(array(
			'layout'					=> $layout,
		));

		if(KRequest::get('cookie.ninjajournal_project_id', 'int'))
		{
			$config->request->append(array(
				'ninjajournal_project_id'	=> KRequest::get('cookie.ninjajournal_project_id', 'int')
			));
		}
		if(KRequest::get('cookie.ninjajournal_task_id', 'int'))
		{
			$config->request->append(array(
				'ninjajournal_task_id'	=> KRequest::get('cookie.ninjajournal_task_id', 'int')
			));
		}
		if(KRequest::get('cookie.duration.minutes', 'int'))
		{
			$config->request->append(array(
				'minutes'	=> KRequest::get('cookie.duration.minutes', 'int')
			));
		}
		if(KRequest::get('cookie.duration.hours', 'int'))
		{
			$config->request->append(array(
				'hours'	=> KRequest::get('cookie.duration.hours', 'int')
			));
		}

		if(KRequest::method() == 'POST' && KRequest::has('post.duration'))
		{
			$minutes 	= KRequest::get('post.duration.minutes', 'int');
			$hours 		= KRequest::get('post.duration.hours', 'int');

			KRequest::set('post.duration.sanitized', $minutes > 0 || $hours > 0);
		}
	
		parent::__construct($config);
		
		$this->_validate = (object)array(
			'required' => array(
				'description'				=> array('html', 'tidy'),
				'ninjajournal_project_id'	=> 'int',
				'ninjajournal_task_id'		=> 'int',
				'duration.sanitized'		=> 'int'
			),
			'msg' => array(
				'description'				=> "You're missing the description",
				'ninjajournal_project_id'	=> "You didn't pick a project",
				'ninjajournal_task_id'		=> "You didn't pick a task",
				'duration.sanitized'		=> "The log duration can't be less than 5 minutes",
			)
		);
		
		//check the acl before carrying out any actions
		$this->registerCallback('before.add', array($this, 'validate'));
		
		//check the acl before carrying out any actions
		$this->registerCallback(array('after.add',
									  'after.cancel'), array($this, 'setLogRedirect'));
		
		//Ugly dirty workaround
		$this->registerCallback(array('after.browse', 'after.read'), array($this, 'workaround'));		
	}

	

	/*
	 * Method for updating the user status
	 *
	 * @return KDatabaseRow
	 */
	protected function _actionUpdate()
	{
		$user = KFactory::get('lib.koowa.user');
		$row  = KFactory::get('site::com.ninjajournal.model.status')
					->by($user->id)
					->getItem();

		$row->setData(KRequest::get('post', 'raw'))
			->save();
		
		return $row;	
	}
	
	/*
	 * @todo Dirty dirty workaround that needs to be solved properly
	 *
	 * @return KDatabaseRow
	 */
	public function workaround(KCommandContext $context)
	{
		$context->status = KHttpResponse::OK;
	}
	
	/*
	 * Overriding the add action to clear the status
	 *
	 * @return KDatabaseRow
	 */
	protected function _actionAdd($data)
	{
		$user = KFactory::get('lib.koowa.user');
		$row  = parent::_actionAdd($data);
		
		$status = KFactory::get('site::com.ninjajournal.model.status')
					->by($user->id)
					->getItem();
		
		if($status->id) $status->delete();
		
		return $row;
	}
	
	/**
	 * Sets the current url as the redirect
	 *
	 * @return	void
	 */
	public function setLogRedirect()
	{
		$this->_redirect = 'index.php?option=com_ninjajournal&view=log'; 
	}
	
	/**
	 * Log delete function
	 *
	 * Sets the model state from the post.
	 *
	 * @return KDatabaseRowset	A rowset object containing the deleted rows
	 */
	protected function _actionDelete($data)
	{		
		$this->getModel()->set(KRequest::get('post', 'string'));

		return parent::_actionDelete($data);
	}
}