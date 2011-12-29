<?php defined('_JEXEC') or die('Restricted access');
 /**
 * @version		$Id: router.php 259 2010-04-03 15:42:28Z stian $
 * @package		NinjaJournal
 * @copyright	Copyright (C) 2007-2010 Ninja Media Group. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

/**
 * Build the link
 *
 * @author	Stian Didriksen <stian@ninjaforge.com>
 * @param	array $query		An array of the query key/value pairs.
 * @return	array $segments		The resulting array for building the SEF optimized url.
 */
function NinjajournalBuildRoute(array &$query)
{
	$segments = array();
	if(isset($query['view']))
	{
		if($query['view'] != 'log' || isset($query['id']))
		{
			$segments[] = $query['view'];
			if(isset($query['id']))
			{
				$segments[] = $query['id'];
				unset($query['id']);
			}
			
		}
		unset($query['view']);
	}

	return $segments;
}

function NinjaboardssBuildRoute(&$query)
{
	$segments = array();
	if(array_key_exists('view', $query))
	{
		$segments[0] = $query['view'];

		if(array_key_exists('id', $query)){
			$model = KFactory::tmp('site::com.ninjaboard.model.'.KInflector::pluralize($segments[0]));
			$item  = $model->id($query['id'])->getItem()->getData();
			$alias = array_key_exists('alias', $item) ? ':'.$item['alias'] : null;
			$segments[1] = KFactory::tmp('lib.koowa.filter.slug')->sanitize($query['id'].$alias);
			$segments[0] = KInflector::pluralize($segments[0]);
			if(array_key_exists('post', $query) && $query['view'] == 'topic'){
				$segments[2] = $query['post'];
			}
		}
		
		unset($query['post']);
		unset($query['view']);
		unset($query['id']);
	}

	return $segments;
}

function NinjaboardssParseRoute($segments)
{
	if(isset($segments[0]))
	{
		$vars['view'] = $segments[0];
		if(isset($segments[1])) {
			$model = KFactory::tmp('site::com.ninjaboard.model.'.$vars['view']);
			if(@$model->sef)
			{
				$vars['id'] = (int) current(explode('-',$segments[1]));
			} else {
				$vars['id'] = (int) $segments[1];
			}
			$vars['view'] = KInflector::singularize($vars['view']);
			if(isset($segments[2]) && isset($segments[3])) {
				$vars[KInflector::singularize($segments[2])] = $segments[3];
			}
		}
	}
	return $vars;
}