<?php
/**
 * @version		$Id: logs.php 592 2011-03-27 21:45:22Z stian $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

/**
 * NinjaJournal Logs Model
 *
 * @package NinjaJournal
 */
class ComNinjajournalModelLogs extends KModelTable
{
	/**
	 * List over teamlogs
	 *
	 * @var array
	 */
	protected $_teamlogs;
	
	/**
	 * A grouped list over logs
	 *
	 * @var array
	 */
	protected $_groupedList;

	/**
	 * Constructor
	 *
	 * @param array An optional associative array of configuration settings.
	 */
	public function __construct(KConfig $config)
	{
		$config->table_behaviors = array('creatable', 'modifiable', KFactory::get('admin::com.ninjajournal.database.behavior.loggable'));
	
		parent::__construct($config);

		/**
		 * Set the state
		 * Since the state state is an integer, 
		 * and have both a 0 and null state, 
		 * we need to use the cmd filter instead of int
		 */
		$this->_state
						->insert('state'	, 'cmd')
						->insert('user'		, 'int')
						->insert('project'	, 'int')
						//These are to preserve states, mostly on the frontend
						->insert('ninjajournal_project_id', 'int')
						->insert('ninjajournal_task_id', 'int')
						->insert('hours', 'int')
						->insert('minutes', 'int');
	}

	protected function _buildQueryColumns(KDatabaseQuery $query)
	{
		parent::_buildQueryColumns($query);

		$query
				->select('prj.title AS project')
				->select('tsk.title AS task')
				->select(array('usr.name AS realname', 'usr.username'))
				->select('DAYNAME(tbl.created_on) AS created_on_dayname')
				->select('TIMESTAMPDIFF(DAY, tbl.created_on, NOW()) AS created_on_daydiff');
	}
	
	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
		parent::_buildQueryJoins($query);

		$query
				->join('left', 'ninjajournal_projects AS prj', 'prj.ninjajournal_project_id = tbl.ninjajournal_project_id')
				->join('left', 'ninjajournal_tasks AS tsk', 'tsk.ninjajournal_task_id = tbl.ninjajournal_task_id')
				->join('left', 'users AS usr', 'usr.id = tbl.user_id');
	}

	/**
	 * Filter list based on search keyword or "state"
	 *
	 * @author Stian Didriksen <stian@ninjaforge.com>
	 * @param  KDatabaseQuery $query
	 */
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		parent::_buildQueryWhere($query);

		if($user = $this->_state->user)
		{
			$query->where('tbl.user_id', '=', $user, 'and');
		}
		
		if($project = $this->_state->project)
		{
			$query->where('tbl.ninjajournal_project_id', '=', $project, 'and');
		}

		if($this->_state->state || $this->_state->state === "0")
		{
			$query->where('tbl.state', '=',  $state = $this->_state->state, 'and');
		}

	   	if($search = $this->_state->search)
		{
			$query->where('tbl.description', 'LIKE',  '%'.$search.'%', 'and');
		}
	}
	
	public function getGroupedList()
	{
		if(!isset($this->_groupedList))
		{
			$this->_groupedList = array();
			foreach(parent::getList() as $log)
			{
				if ( (int)$log->created_on_daydiff === 0 )
				{
					$this->_groupedList[JText::_('Today')][] = $log;
				}
				elseif ( (int)$log->created_on_daydiff === 1 )
				{
					$this->_groupedList[JText::_('Yesterday')][] = $log;
				}
				else
				{
					$this->_groupedList[KFactory::get('admin::com.ninja.helper.date')->beautiful(array('date' => $log->created_on, 'title' => false))][] = $log;
				}
			}
		}
		
		return $this->_groupedList;
	}
	
	public function getTeamlogs()
	{
		if(!isset($this->_teamlogs))
		{
			$table = KFactory::get($this->getTable());
			$query = $table->getDatabase()->getQuery();
			$user  = KFactory::get('lib.koowa.user')->id;
			$ids   = array($user);

			$query
					->select(array('sts.by AS user_id', 'sts.message', 'sts.on AS last_update', 'usr.name AS realname'))
					->distinct('sts.by')
					->where('sts.by', '!=', $user)
					->where('sts.on', '>', gmdate('Y-m-d H:i:s', strtotime('-7 days')))
					->join('left', 'users AS usr', 'usr.id = sts.by')
					->from('ninjajournal_status AS sts')
					->order('sts.on', 'desc');

			$users = $table->select($query, KDatabase::FETCH_ARRAY_LIST);

			foreach($users as $row) $ids[] = $row['user_id'];

			$query = $table->getDatabase()->getQuery();

			$query
					->select(array('tbl.user_id', 'tbl.created_on AS last_update', 'usr.name AS realname'))
					->distinct('tbl.user_id')
					->where('tbl.user_id', 'NOT IN', $ids)
					->join('left', 'users AS usr', 'usr.id = tbl.user_id')
					->from('ninjajournal_logs AS tbl')
					->order('tbl.created_on', 'ASC');
			$query->columns[] = '"" AS message';

			$users = $table->select($query, KDatabase::FETCH_ARRAY_LIST);

			foreach($users as $item)
			{
				$this->_teamlogs[$item['user_id']] = (object) $item;
				
				$this->_teamlogs[$item['user_id']]->logs = KFactory::tmp($this->getIdentifier())
																		->user($item['user_id'])
																		->sort('created_on')
																		->direction('desc')
																		->limit(4)
																		->getList();
			}
		}
		
		return $this->_teamlogs;
	}

	/**
	 * Overrides getItem to set default values according to state
	 *
	 * @author Stian Didriksen <stian@ninjaforge.com>
	 * @return KDatabaseRowInterface
	 */
	public function getItem()
	{
		$item = parent::getItem();

		foreach(array('ninjajournal_project_id', 'ninjajournal_task_id', 'hours', 'minutes') as $property)
		{
			if(!isset($item->$property) || !$item->$property) $item->$property = $this->_state->$property;
		}

		return $item;
	}
}