<?php
/**
 * @version		$Id: status.php 594 2011-03-27 22:08:54Z stian $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

/**
 * NinjaJournal Status Model
 *
 * @package NinjaJournal
 */
class ComNinjajournalModelStatus extends KModelTable
{
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		$config->table_behaviors = array(KFactory::get('site::com.ninjajournal.database.behavior.statusable'));

		parent::__construct($config);
	}
}