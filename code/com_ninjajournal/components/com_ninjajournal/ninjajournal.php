<?php
/**
 * @version		$Id: ninjajournal.php 591 2011-03-27 21:21:17Z stian $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

// no direct access
defined('KOOWA') or die("Koowa isn't available, or file is accessed directly"); 
 
$ninja = JPATH_ADMINISTRATOR.'/components/com_ninja/ninja.php';
if(!file_exists($ninja)) {
	return JError::raiseWarning(0, JText::_('The Ninja Framework component (com_ninja) is required for this component to run.'));
}
require_once $ninja;

/** 
* If koowa is unavailable
* Abort the dispatcher
*/
if(!defined('KOOWA')) {
	return JError::raiseWarning(0, JText::_('This component cannot run without Nooku Framework.'));
}

//lets also map com_ninja's default helper
KFactory::map('admin::com.ninjacontent.template.helper.default', 'admin::com.ninja.helper.default');


// We like code reuse, so we map the frontend models to the backend models
KFactory::map('site::com.ninjajournal.model.logs',		'admin::com.ninjajournal.model.logs');
KFactory::map('site::com.ninjajournal.model.todos',		'admin::com.ninjajournal.model.todos');
KFactory::map('site::com.ninjajournal.model.tasks',		'admin::com.ninjajournal.model.tasks');
KFactory::map('site::com.ninjajournal.model.types',		'admin::com.ninjajournal.model.types');
KFactory::map('site::com.ninjajournal.model.projects',	'admin::com.ninjajournal.model.projects');
KFactory::map('lib.koowa.database.behavior.loggable',	'admin::com.ninjajournal.behavior.loggable');

JHTML::_('behavior.mootools');

// This is to avoid redirects if the view isn't set.
if(!KRequest::has('get.view', 'cmd')) KRequest::set('get.view', 'log');

// Create the component dispatcher
echo KFactory::get('site::com.ninjajournal.dispatcher')->dispatch(KRequest::get('get.view', 'cmd', 'logs'));