<?php
/**
 * @version		$Id: users.php 375 2010-08-12 20:53:13Z stian $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

/**
 * NinjaJournal Users Model
 *
 * @package NinjaJournal
 */
class ComNinjajournalModelUsers extends KModelTable
{
	/**
	 * Constructor
	 *
	 * @param array An optional associative array of configuration settings.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		$this->_state->insert('productive', 'boolean', false);
	}

	protected function _buildQueryColumns(KDatabaseQuery $query)
	{
		parent::_buildQueryColumns($query);

		//@TODO do we really need to make sure we only run this when search is defined?
		/*if($this->_state->search)		*/$query->select(array('tbl.id AS value', "CONCAT(tbl.username, ' ~ ', tbl.name) AS text"));
	}
	
	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
		if($this->_state->productive) {
			$query
					->join('left', 'ninjajournal_logs AS log', 'log.user_id = tbl.id')
					->join('left', 'ninjajournal_todos AS todo', 'todo.user_id = tbl.id');
		}
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

	   	if($search = $this->_state->search)
		{
			$query
					->where('tbl.username', 'LIKE',  '%'.$search.'%', 'or')
					->where('tbl.name', 'LIKE',  '%'.$search.'%', 'or')
					->where('tbl.email', 'LIKE',  '%'.$search.'%', 'or');
		}
		
		if($this->_state->productive) $query->where('IFNULL(log.user_id, todo.user_id)', '!=', 'NULL');
	}
}