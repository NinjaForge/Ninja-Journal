<?php
/**
 * @version		$Id: todos.php 444 2010-09-28 18:42:37Z stian $
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
class ComNinjajournalModelTodos extends KModelTable
{
	/**
	 * Constructor
	 *
	 * @param array An optional associative array of configuration settings.
	 */
	public function __construct(KConfig $config)
	{
		$config->table_behaviors = array('creatable', 'modifiable');
	
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
						->insert('closed'	, 'boolean', true);
	}

	protected function _buildQueryColumns(KDatabaseQuery $query)
	{
		parent::_buildQueryColumns($query);

		$query->select('usr.name AS username');
	}
	
	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
		parent::_buildQueryJoins($query);

		$query->join('left', 'users AS usr', 'usr.id = tbl.user_id');
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

		if($this->_state->closed === false)
		{
			$query->where('tbl.state', '!=',  2, 'and');
			//Koowa quotes Y-m-d H:i:s
			$thirtyDaysAgo = gmdate('YmdHis', strtotime('-7 days'));
			$query->where("(tbl.created_on > '$thirtyDaysAgo' OR tbl.modified_on > '$thirtyDaysAgo' OR `tbl`.`state` = '0')", '=', 1);
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
}