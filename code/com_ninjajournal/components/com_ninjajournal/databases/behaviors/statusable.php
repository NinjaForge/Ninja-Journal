<?php
/**
 * @version		$Id: statusable.php 468 2010-11-20 13:10:05Z richie $
 * @package		NinjaJournal
 * @copyright	Copyright (C) 2010 NinjaForge. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */ 

/**
 * A behavior that updates the users' status
 *
 * @author		Stian Didriksen <stian@ninjaforge.com>
 * @package     NinjaJournal
 * @subpackage 	Behaviors
 */
class ComNinjajournalDatabaseBehaviorStatusable extends KDatabaseBehaviorAbstract
{
	/**
	 * Set created information
	 * 	
	 * Requires an created_on and created_by field in the table schema
	 * 
	 * @return boolean	False if failed.
	 */
	protected function _beforeTableInsert(KCommandContext $context)
	{
		$row = $context['data']; //get the row data being inserted
		
		$row->by  = (int) KFactory::get('lib.koowa.user')->get('id');
		$row->on  = gmdate('Y-m-d H:i:s');
		
		return true;
	}
	
	/**
	 * Set modified information
	 * 	
	 * Requires a modified_on and modified_by field in the table schema
	 * 
	 * @return boolean	False if failed.
	 */
	protected function _beforeTableUpdate(KCommandContext $context)
	{
		$row = $context['data']; //get the row data being inserted
		
		$row->on = gmdate('Y-m-d H:i:s');
		$row->by = (int) KFactory::get('lib.koowa.user')->get('id');
	
		return true;
	}
}