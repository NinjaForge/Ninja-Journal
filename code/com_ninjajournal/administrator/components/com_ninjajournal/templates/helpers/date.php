<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

class ComNinjajournalTemplateHelperDate extends KTemplateHelperAbstract
{
	public function formatTimespan($config = array())
	{
		$config = new KConfig($config);
		
		$config->append(array(
			'time' => false,
			'format' => 'hr mi'
		));
		
		$hours   = intval($config->time / 60);
		$minutes = intval($config->time % 60);

		if (strlen($minutes) < 2) {
			$minutes = '0' . $minutes;
		}

		$config->format = str_replace('hr mi', ($hours > 0 ? $hours.' '.JText::_('Hrs.') : '').' '.
			($minutes > 0 ? $minutes.' '.JText::_('Min.') : '') , $config->format);

		$config->format = str_replace('HR MI', ($hours > 0 ? ($hours == 1 ? JText::_('Hour') : JText::_('Hours')) : '').' '.
			($minutes > 0 ? ($minutes == 1 ? JText::_('Minute') : JText::_('Minutes')) : '') , $config->format);

		$config->format = str_replace('h:m', $hours.':'.$minutes, $config->format);
		
		return trim($config->format);
	}

}