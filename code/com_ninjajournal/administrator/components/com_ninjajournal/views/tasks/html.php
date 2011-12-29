<?php
/**
 * @version		$Id: html.php 292 2010-07-01 00:41:53Z stian $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

class ComNinjajournalViewTasksHtml extends ComNinjajournalViewHtml
{
	public function display()
	{
		$projects = KFactory::tmp('admin::com.ninjajournal.model.projects')->getTotal();
		$types    = KFactory::tmp('admin::com.ninjajournal.model.types')   ->getTotal();
		if(!$projects && !$types)
		{
			$this->_createToolbar()->reset();
			$this->placeholder = KFactory::get('admin::com.ninja.helper.placeholder', 
				array('notice' => JText::_('You need projects and types before you can create tasks.'))
			)
			->append('projects', array('href' => $this->createRoute('view=project')))
			->append('types', array('href' => $this->createRoute('view=type')));
		}
		elseif(!$projects || !$types)
		{
			$this->_createToolbar()->reset();
			$what 				= !$projects ? 'projects' : 'types';
			$this->length 		= $$what;
			$this->placeholder	= $this->placeholder($what, false, false, JText::_("You need %s before you can create tasks."));
		}
		else
		{
			$this->length		= KFactory::tmp($this->getModel())->getTotal();
			$this->placeholder	= $this->placeholder();
		}
 
		return parent::display();
	}
}