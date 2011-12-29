<?php
/**
 * @version		$Id: log.php 370 2010-08-12 13:17:28Z stian $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

/**
 * NinjaJournal Default Controller
 *
 * @package NinjaJournal
 */
class ComNinjajournalControllerLog extends ComNinjajournalControllerDefault
{
	/**
	 * Constructor
	 *
	 * @param array An optional associative array of configuration settings.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		// Set the ordering states if they're not set already
		if(!KRequest::has('get.order'		, 'cmd'))	KRequest::set('get.order'		, 'created_on'	, 'cmd');
		if(!KRequest::has('get.direction'	, 'cmd'))	KRequest::set('get.direction'	, 'desc'		, 'cmd');
	}
}