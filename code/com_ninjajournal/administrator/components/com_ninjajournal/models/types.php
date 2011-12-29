<?php
/**
 * @version		$Id: types.php 209 2010-03-10 22:09:10Z stian $
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
class ComNinjajournalModelTypes extends KModelTable
{
	/**
	 * Filter list based on search keyword
	 *
	 * @author Stian Didriksen <stian@ninjaforge.com>
	 * @param  KDatabaseQuery $query
	 */
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		parent::_buildQueryWhere($query);

	   	if($search = $this->_state->search)
		{
			$query->where('tbl.title', 'LIKE',  '%'.$search.'%');
		}
	}
}