<?php
/**
 * @version		$Id: default.php 468 2010-11-20 13:10:05Z richie $
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
class ComNinjajournalControllerDefault extends ComNinjaControllerView
{

	/**
	 * Configuration array for the validate function
	 *
	 * @TODO make dynamic
	 *
	 * @var array
	 */
	protected $_validate = array(
		'required'	=> array(),
		'msg'		=> array()
	);

	/**
	 * Constructor
	 *
	 * @param array An optional associative array of configuration settings.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		// Register actions aliasses
		$this->registerActionAlias('close', 'open');
		
		$this->registerCallback(array('after.open',
									  'after.close'), array($this, 'setMessage'));
	}

	/*
	 * Generic method to modify the open/closed state of and item(s)
	 *
	 * @return KDatabaseRowset
	 */
	protected function _actionOpen(KCommandContext $context)
	{
		$data  = KConfig::toData($context->data);
		$state = $context->action == 'close' ? 1 : 0;
		
		$rowset = $this->getModel()
					->getTable()
					->select($data['id'])->setData(array('state' => $state));
					
		$rowset->save();
		
		return $rowset;
	}
	
	/**
	 * Validate form data
	 *
	 * @author	Stian Didriksen <stian@ninjaforge.com>
	 * @param $param
	 * @return return type
	 */
	public function validate()//array $required, $msg = '%s is required.')
	{
		$isValid = true;
		foreach ($this->_validate->required as $field => $filter)
		{
			if(!KRequest::get('post.'.$field, $filter))
			{
				$warning = is_array($this->_validate->msg) && isset($this->_validate->msg[$field]) ? JText::_($this->_validate->msg[$field]) : sprintf(JText::_($this->_validate->msg), JText::_(KInflector::humanize($field)));
				JError::raiseWarning(403, $warning);
				$isValid = false;
			}
		}
		if(!$isValid)
		{
			$this->execute('cancel');
			return false;
		}
	}
}