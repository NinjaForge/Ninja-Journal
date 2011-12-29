<?php defined( '_JEXEC' ) or die( 'Restricted access' );
/**
 * @version		$Id: upgrade.php 592 2011-03-27 21:45:22Z stian $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2011 NinjaForge. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://ninjaforge.com
 */
 
/** 
* If koowa is unavailable
* Abort the dispatcher
*/
if(!defined('KOOWA')) {
	return JError::raiseWarning(0, JText::_('This component cannot run without Nooku Framework.'));
}

$db	= KFactory::get('lib.koowa.database');

//Migrate statuses to status
try {
	$db->getTableColumns('ninjajournal_statuses');
	$db->execute('DROP TABLE IF EXISTS `#__ninjajournal_status`;');
	$db->execute('RENAME TABLE `#__ninjajournal_statuses` TO `#__ninjajournal_status`;');
} catch(KDatabaseException $e) {
	//Fail silently
}

foreach(array(
	'logs',
	'projects',
	'tasks',
	'todos',
	'types',
	'status'
) as $name)
{
	$tables[] = 'ninjajournal_'.$name;
}
$tables = $db->getTableColumns($tables);
foreach($tables as $name => $fields)
{
	unset($tables[$name]);
	$tables[str_replace('ninjajournal_', '', $name)] = $fields;
}

//Run upgrade routines if the types table don't have a 'enabled' column
if(!isset($tables['types']['enabled']))
{
	$db->execute("ALTER TABLE #__ninjajournal_logs CHANGE COLUMN `description` `description` text NOT NULL COMMENT '@Filter(".'"html"'.")'");

	$db->execute('ALTER TABLE #__ninjajournal_logs ENGINE = MyISAM');

	$db->execute("ALTER TABLE #__ninjajournal_projects CHANGE COLUMN `description` `description` text NOT NULL COMMENT '@Filter(".'"html"'.")'");

	$db->execute("ALTER TABLE #__ninjajournal_tasks CHANGE COLUMN `description` `description` text NOT NULL COMMENT '@Filter(".'"html"'.")'");

	$db->execute('ALTER TABLE #__ninjajournal_tasks ENGINE = MyISAM');

	$db->execute("ALTER TABLE #__ninjajournal_todos CHANGE COLUMN `description` `description` text NOT NULL COMMENT '@Filter(".'"html"'.")'");

	$db->execute('ALTER TABLE #__ninjajournal_todos ENGINE = MyISAM');
	
	$db->execute("ALTER TABLE #__ninjajournal_types ADD `enabled` tinyint(1) NOT NULL DEFAULT 1  AFTER `title`;");
	
	$db->execute("ALTER TABLE #__ninjajournal_status CHANGE COLUMN `message` `message` text NOT NULL COMMENT '@Filter(".'"html"'.")'");
		
	$db->execute('ALTER TABLE #__ninjajournal_status ENGINE = MyISAM');
}