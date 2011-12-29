<?php
/**
 * @version		$Id: fusionchart.php 468 2010-11-20 13:10:05Z richie $
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Helper for generation FusionCharts
 */
class ComNinjajournalTemplateHelperFusionchart extends KObject
{
	public function __construct()
	{
		if(!class_exists('FusionCharts')) require_once 'fusioncharts/FusionCharts_Gen.php';
		$this->js = KFactory::get('admin::com.ninja.helper.default')->charts('/FusionCharts.js');
		$this->swfpath = dirname($this->js).'/';
		
		KFactory::get('lib.koowa.document')->addScript($this->js);
		
		$config = new KObject;
		$this->params = $config->set(array(
			'numberPrefix' => null,
			'decimalPrecision' => 0,
			'formatNumberScale' => 1,
			'showNames' => 1,
			'showValues' => 0,
			'pieBorderAlpha' => 100,
			'shadowXShift' => 4,
			'shadowYShift' => 4,
			'shadowAlpha' => 60,
			'pieBorderColor' => 'f1f1f1',
			'yAxisName' => JText::_('Minutes')
		));
	}
	public function create( $caption = 'Project stats', $xAxisName = 'Types', $width = 700, $height = 300, $chart = 'Pie2D' )
	{
		$chart = new FusionCharts($chart, $width, $height);
		$chart->setSWFPath($this->swfpath);
		
		$this->params->set(array(
			'caption'	=> JText::_($caption),
			'xAxisName'	=> JText::_($xAxisName)
		));

		$chart->setChartParams((string) $this);
		
		return $chart;
	}
	
	public function __toString()
	{
		return str_replace('"', '', KHelperArray::toString($this->params->get(), '=', ';'));
	}
}