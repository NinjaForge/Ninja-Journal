<?php
/**
 * @version		$Id: projects.php 370 2010-08-12 13:17:28Z stian $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

/**
 * NinjaJournal Projects Model
 *
 * @package NinjaJournal
 */
class ComNinjajournalModelProjects extends KModelTable
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
		$this->_state->insert('state'   , 'cmd');
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
			$query->where('tbl.state', '=',  $state = $this->_state->state, 'and');
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