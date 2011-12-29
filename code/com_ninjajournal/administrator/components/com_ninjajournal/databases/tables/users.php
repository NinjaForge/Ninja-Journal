<?php
/**
 * @version		$Id: users.php 468 2010-11-20 13:10:05Z richie $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

/**
 * NinjaJournal Users Table
 *
 * @package NinjaJournal
 */
class ComNinjajournalDatabaseTableUsers extends KDatabaseTableAbstract
{
	/**
	 * Constructor
	 *
	 * @param array An optional associative array of configuration settings.
	 */
	public function __construct(KConfig $config)
	{
		//$options['primary'] = 'id';
		//$config->column_map =  array('id' => 'id');
		$config->base	= 'users';
		$config->name	= 'users';
		$config->identity_column = 'id';
		
		parent::__construct($config);
	}
}