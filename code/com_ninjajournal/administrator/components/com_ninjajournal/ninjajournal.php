<?php
 /**
 * @version		$Id: ninjajournal.php 591 2011-03-27 21:21:17Z stian $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

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

KFactory::map('admin::com.ninjajournal.template.helper.default', 'admin::com.ninja.helper.default');

// Create the component dispatcher
echo KFactory::get('admin::com.ninjajournal.dispatcher')->dispatch(KRequest::get('get.view', 'cmd', 'dashboard'));