<?php
/**
 * @version		$Id: tasks.php 375 2010-08-12 20:53:13Z stian $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

/**
 * NinjaJournal Tasks Model
 *
 * @package NinjaJournal
 */
class ComNinjajournalModelTasks extends KModelTable
{
	/**
	 * Constructor
	 *
	 * @param array An optional associative array of configuration settings.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		/**
		 * Set the state
		 * Since the state state is an integer, 
		 * and have both a 0 and null state, 
		 * we need to use the cmd filter instead of int
		 */
		$this->_state
						->insert('state'	, 'cmd')
						->insert('project'	, 'int')
						->insert('type'		, 'int');
	}

	protected function _buildQueryColumns(KDatabaseQuery $query)
	{
		parent::_buildQueryColumns($query);

		$query
				->select('prj.title AS project')
				->select('typ.title AS type');
		
		// If the project is global, give it a title here
		//$query->columns[] = "IF(prj.title, prj.title, '".JText::_('Global (All Projects)')."') as project";
	}
	
	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
		parent::_buildQueryJoins($query);

		$query
				->join('left', 'ninjajournal_projects AS prj', 'prj.ninjajournal_project_id = tbl.ninjajournal_project_id')
				->join('left', 'ninjajournal_types AS typ', 'typ.ninjajournal_type_id = tbl.ninjajournal_type_id');
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

		$isState = $this->_state->state || $this->_state->state === "0";

		if($isState)
		{
			$query->where('tbl.state', '=',  $this->_state->state, 'and');
		}
		
		if($project = $this->_state->project)
		{
			$query->where('tbl.ninjajournal_project_id', 'IN',  array(0, $project), 'and');
		}
		
		if($type = $this->_state->type)
		{
			$query->where('tbl.ninjajournal_type_id', '=',  $type, 'and');
		}

	   	if($search = $this->_state->search)
		{
			if($isState)
			{
				// Koowa can't group where statements yet using where(), so we're using raw sql to do it meanwhile.
				$query->where[] = "AND ( `tbl`.`title` LIKE '%other%' OR `tbl`.`description` LIKE '%other%' )";
			}
			else
			{
				$query
					->where('tbl.title', 'LIKE',  '%'.$search.'%', 'or')
					->where('tbl.description', 'LIKE',  '%'.$search.'%', 'or');
			}
		}
	}
}