<?php
/**
 * @version		$Id: reports.php 375 2010-08-12 20:53:13Z stian $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

/**
 * NinjaJournal Type Controller
 *
 * @package NinjaJournal
 */
class ComNinjajournalModelReports extends KModelTable
{

	/**
	 * Period report
	 *
	 * @var array
	 */
	protected $_period;
	
	/**
	 * User report
	 *
	 * @var array
	 */
	protected $_user;
	
	/**
	 * Project report
	 *
	 * @var array
	 */
	protected $_project;
	
	/**
	 * Constructor
	 *
	 * @param array An optional associative array of configuration settings.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state
						->insert('project'	, 'int')
						->insert('user'		, 'int')
						->insert('from'		, 'date')
						->insert('until'	, 'date');
	}
	
	protected function _buildQueryColumns(KDatabaseQuery $query)
	{
		parent::_buildQueryColumns($query);

		/* @TODO 	This resulted in occational SQL syntax errors with the ordering, 
					look into wether it's worth making the joins conditional again.
		if(!$this->_state-> project)
		{
			$query->select('prj.title AS project');
		}
		
		if($this->_state->user || $this->_state->project)
		{
			$query->select('tsk.title AS task');
		}
		if($this->_state->project)
		{
			$query->select(array('typ.ninjajournal_type_id', 'typ.title AS type', 'usr.username'));
		}
		//*/
		$query->select(array(
								'prj.title AS project',
								'tsk.title AS task',
								'typ.ninjajournal_type_id',
								'typ.title AS type',
								'usr.username'
		));

		if(!$this->_state->project && !$this->_state->user)
		{
			$query->columns[] = 'SUM(`tbl`.`duration`) AS `duration`';
		}
	}
	
	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
		parent::_buildQueryJoins($query);

		/* @TODO 	This resulted in occational SQL syntax errors with the ordering, 
					look into wether it's worth making the joins conditional again.
		if(!$this->_state->project)
		{
			$query->join('left', 'ninjajournal_projects AS prj', 'prj.ninjajournal_project_id = tbl.ninjajournal_project_id');
		}
		
		if($this->_state->user || $this->_state->project)
		{
			$query->join('left', 'ninjajournal_tasks AS tsk', 'tsk.ninjajournal_task_id = tbl.ninjajournal_task_id');
		}
		
		if($this->_state->project)
		{
			$query
					->join('left', 'ninjajournal_types AS typ', 'typ.ninjajournal_type_id = tsk.ninjajournal_type_id')
					->join('left', 'users AS usr', 'usr.id = tbl.user_id');
		}
		//*/
			$query
					->join('left', 'ninjajournal_tasks AS tsk', 'tsk.ninjajournal_task_id = tbl.ninjajournal_task_id')
					->join('left', 'ninjajournal_projects AS prj', 'prj.ninjajournal_project_id = tbl.ninjajournal_project_id')
					->join('left', 'ninjajournal_types AS typ', 'typ.ninjajournal_type_id = tsk.ninjajournal_type_id')
					->join('left', 'users AS usr', 'usr.id = tbl.user_id');
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		parent::_buildQueryWhere($query);
		$state = $this->_state;
		
		if($user	= $state->user)		$query->where('tbl.user_id'		, '=' , $user				 , 'and');
		if($project	= $state->project)	$query->where('tbl.ninjajournal_project_id', '=' , $project	 , 'and');
		if($from	= $state->from)		$query->where('tbl.created_on'	, '>=', $from  . ' 00:00:00', 'and');
		if($until	= $state->until)	$query->where('tbl.created_on'	, '<=', $until . ' 23:59:59', 'and');
	}
	
	protected function _buildQueryGroup(KDatabaseQuery $query)
	{
		if(!$this->_state->project && !$this->_state->user) $query->group('tbl.ninjajournal_project_id');
	}

	/**
	 * Get a project report
	 *
	 * @author Stian Didriksen <stian@ninjaforge.com>
	 * @return array
	 */
	public function getProjectReport()
	{
		// Get the data if it doesn't already exist
		if (!isset($this->_project))
		{
			// init vars
			$report = array('data' => array(), 'total' => 0, 'type' => array(), 'user' => array());
	
        	foreach (parent::getList() as $row)
			{
				if (!isset($report['type'][$row->ninjajournal_type_id])) {
					$report['type'][$row->ninjajournal_type_id] = array(
						'id' => $row->user_id,
						'name' => $row->type,
						'total' => $row->duration);
				} else {
					$report['type'][$row->ninjajournal_type_id]['total'] += $row->duration;
				}
	
				if (!isset($report['type'][$row->ninjajournal_type_id]['task'][$row->ninjajournal_task_id])) {
					$report['type'][$row->ninjajournal_type_id]['task'][$row->ninjajournal_task_id] = array(
						'id' => $row->ninjajournal_task_id,
						'name' => $row->task,
						'log' => array($row->id));
				} else {
					$report['type'][$row->ninjajournal_type_id]['task'][$row->ninjajournal_task_id]['log'][] = $row->id;
				}
	
				if (!isset($report['user'][$row->user_id])) {
					$report['user'][$row->user_id] = array(
						'id' => $row->user_id,
						'name' => $row->username,
						'total' => $row->duration);
				} else {
					$report['user'][$row->user_id]['total'] += $row->duration;
				}
	
				$report['data'][$row->id] = array(
					'id' => $row->id,
					'log' => $row->description,
					'username' => $row->username,
					'date' => $row->created_on,
					'duration' => $row->duration);
				
				$report['total'] += $row->duration;
			}
			// sort type & user data
			$compare = create_function('$a,$b', 'return $a["total"] == $b["total"] ? 0 : $a["total"] > $b["total"] ? -1 : 1;');
			usort($report['type'], $compare);
			usort($report['user'], $compare);
			
			$this->_project = $report;
		}

		return $this->_project;
	}

	/**
	 * Get a user report
	 *
	 * @author Stian Didriksen <stian@ninjaforge.com>
	 * @return array
	 */
	public function getUserReport()
	{
		// Get the data if it doesn't already exist
		if (!isset($this->_user))
		{
			// init vars
			$config            =& JFactory::getConfig();		
			$tzoffset          = $config->getValue('config.offset');
			$report = array('data' => array(), 'total' => 0, 'project' => array());
	
        	foreach (parent::getList() as $row)
			{
				$date  = JFactory::getDate($row->created_on, $tzoffset);
				$week  = date('Y-W', $date->toUnix());
	
				if (!isset($report['project'][$row->ninjajournal_project_id])) {
					$report['project'][$row->ninjajournal_project_id] = array(
						'id' => $row->ninjajournal_project_id,
						'name' => $row->project,
						'total' => $row->duration);
				} else {
					$report['project'][$row->ninjajournal_project_id]['total'] += $row->duration;
				}
	
				if (!isset($report['data'][$week]['total'])) {
					$report['data'][$week]['title'] = date('F Y', $date->toUnix()).' - '.JText::_('Week').' '.date('W', $date->toUnix());
					$report['data'][$week]['total'] = $row->duration;
				} else {
					$report['data'][$week]['total'] += $row->duration;
				}
	
				$report['data'][$week]['logs'][] = array(
					'id' => $row->id,
					'project_name' => $row->project,
					'task_name' => $row->task,
					'log' => $row->description,
					'date' => $row->created_on,
					'duration' => $row->duration);
				
				$report['total'] += $row->duration;
				
			}
			// sort project data
			$compare = create_function('$a,$b', 'return $a["total"] == $b["total"] ? 0 : $a["total"] > $b["total"] ? -1 : 1;');
			usort($report['project'], $compare);
			
			$this->_user = $report;
		}

		return $this->_user;
	}

	/**
	 * Get a report of a specific period
	 *
	 * @author Stian Didriksen <stian@ninjaforge.com>
	 * @return array
	 */
	public function getPeriodReport()
	{
		// Get the data if it doesn't already exist
		if (!isset($this->_period))
		{
			$report = array('data' => array(), 'total' => 0);
	
        	foreach (parent::getList() as $row)
			{
				$report['data'][] = array(
					'id'		=> $row->ninjajournal_project_id,
					'name'		=> $row->project,
					'duration'	=> $row->duration
				);
				$report['total'] += $row->duration;
			}

			$this->_period = $report;
		}

		return $this->_period;
	}
}