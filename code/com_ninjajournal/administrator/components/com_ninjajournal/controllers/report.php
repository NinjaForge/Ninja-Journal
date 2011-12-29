<?php
/**
 * @version		$Id: report.php 370 2010-08-12 13:17:28Z stian $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

/**
 * NinjaJournal Report Controller
 *
 * @package NinjaJournal
 */
class ComNinjajournalControllerReport extends ComNinjajournalControllerDefault
{
	/**
	 * Constructor
	 *
	 * @param array An optional associative array of configuration settings.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);		
		
		if(!KRequest::has('get.from', 'date'))
		{
			KRequest::set('get.from', gmdate('Y-m-d', strtotime('-29 days')), 'date');
		}
		if(!KRequest::has('get.until', 'date'))
		{
			KRequest::set('get.until', gmdate('Y-m-d'), 'date');
		}
	}
}