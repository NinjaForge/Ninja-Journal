<?php
/**
 * @version		$Id: reports.php 468 2010-11-20 13:10:05Z richie $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

/**
 * NinjaJournal Reports Table
 *
 * @package NinjaJournal
 */
class ComNinjajournalDatabaseTableReports extends KDatabaseTableAbstract
{
	/**
	 * Constructor
	 *
	 * @param array An optional associative array of configuration settings.
	 */
	public function __construct(KConfig $config)
	{
		//Reports should use the logs table, as there is no reports table.
		$config->name = 'ninjajournal_logs'; 
		$config->base = 'ninjajournal_logs';
		
		parent::__construct($config);
	}
}