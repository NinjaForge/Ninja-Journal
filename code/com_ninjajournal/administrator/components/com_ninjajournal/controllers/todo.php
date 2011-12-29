<?php
/**
 * @version		$Id: todo.php 596 2011-03-27 22:09:14Z stian $
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
class ComNinjajournalControllerTodo extends ComNinjajournalControllerDefault
{
	/**
	 * Constructor
	 *
	 * @param array An optional associative array of configuration settings.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		// Register actions aliasses
		$this->registerActionAlias('done', 'open');
		
		$this->registerCallback('after.done', array($this, 'setMessage'));

	}

	/*
	 * Generic method to modify the open/closed state of and item(s)
	 *
	 * @return KDatabaseRowset
	 */
	protected function _actionOpen(KCommandContext $context)
	{
		$data 	= KConfig::toData($context->data);
		$states = array('open' => 0, 'done' => 1, 'close' => 2);
		
		$rowset = $this->getModel()
					->getTable()
					->select($data['id'])->setData(array('state' => $states[$data['action']]));
		$rowset->save();
		
		return $rowset;
	}
}