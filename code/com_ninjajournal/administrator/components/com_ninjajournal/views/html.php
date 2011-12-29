<?php
/**
 * @version		$Id: html.php 468 2010-11-20 13:10:05Z richie $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

class ComNinjajournalViewHtml extends ComNinjaViewDefault
{
	public function display()
	{
		//Load the js message box plugin
		KFactory::get('admin::com.ninja.helper.default')->js('/Roar.js');
		KFactory::get('admin::com.ninja.helper.default')->css('/Roar.css');
		
		// Display the toolbar
		$toolbar = $this->_createToolbar();
		$path = KFactory::get($this->getModel())->getIdentifier()->path;

		if(KInflector::isPlural(KFactory::get($this->getModel())->getIdentifier()->name) && $this->getName() != 'dashboard')
		{
			$this->_mixinMenubar();
		}

		if ($this->getName() == 'dashboard')
		{
			$toolbar->reset();
			$this->_document->setBuffer(false, 'modules', 'submenu');
		}
		else
		{
			$toolbar->append('spacer');
		}

		$toolbar->append(KFactory::get('admin::com.ninja.toolbar.button.about'));

		return parent::display();
	}
}