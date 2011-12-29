<?php
/**
 * @version		$Id: html.php 468 2010-11-20 13:10:05Z richie $
 * @category	NinjaJournal
 * @copyright	Copyright (C) 2007 - 2010 NinjaForge. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://ninjaforge.com
 */

class ComNinjajournalViewReportsHtml extends ComNinjaViewDefault
{
	
	/**
	 * Colors array for the charts
	 *
	 * @var array
	 */
	public $colors = array();

	public function display()
	{
		$this->user		= KFactory::get('lib.joomla.user');
		$model			= KFactory::get($this->getModel());
		$this->state	= $model->getState();
		KFactory::get($this->getToolbar())->reset()->append('about');

		// set date presets
		$date   = JFactory::getDate();
		$date   = $date->toUnix();
		$date   = mktime(0, 0, 0, date('n', $date), date('j', $date), date('Y', $date));
		$monday = (date('w', $date) == 1) ? $date : strtotime('last Monday', $date);
		$date_presets['last30'] = array(
			'name'  => 'Last 30 days',
			'from'  => date('Y-m-d', strtotime('-29 day', $date)),
			'until' => date('Y-m-d', $date));
		$date_presets['today'] = array(
			'name'  => 'Today',
			'from'  => date('Y-m-d', $date),
			'until' => date('Y-m-d', $date));
		$date_presets['week'] = array(
			'name'  => 'This Week',
			'from'  => date('Y-m-d', $monday),
			'until' => date('Y-m-d', strtotime('+6 day', $monday)));
		$date_presets['month'] = array(
			'name'  => 'This Month',
			'from'  => date('Y-m-d', mktime(0, 0, 0, date('n', $date), 1, date('Y', $date))),
			'until' => date('Y-m-d', mktime(0, 0, 0, date('n', $date)+1, 0, date('Y', $date))));
		$date_presets['year'] = array(
			'name'  => 'This Year',
			'from'  => date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y', $date))),
			'until' => date('Y-m-d', mktime(0, 0, 0, 12, 31, date('Y', $date))));
		
		// simpledate select
		$select  = '';
		$options = array(JHTML::_('select.option', '', '- '.JText::_('Select Period').' -', 'text', 'value'));
		foreach ($date_presets as $name => $value) {
			$options[] = JHTML::_('select.option', $name, JText::_($value['name']), 'text', 'value');
			if ($value['from'] == $this->state->from && $value['until'] == $this->state->until) {
				$select = $name;
			}
		}
		$lists['select_date'] = JHTML::_('select.genericlist', $options, 'period', 'class="inputbox" size="1"', 'text', 'value', $select);

		if ($this->state->project)
		{
			// create report
			$this->report        = $model->sort(array('ninjajournal_type_id', 'ninjajournal_task_id', 'created_on'))->getProjectReport();
			$this->layout  = 'report_project';

			// create chart
			$charts[] = KFactory::get('admin::com.ninjajournal.template.helper.fusionchart')->create('Type stats', 'Types', 400, 300);
			$this->colors = $this->getColorscheme('#224565', count($this->report['type']));
			foreach ($this->report['type'] as $type)
			{
				$share = $type['total'] / $this->report['total'] >= 0.05;
				$param = $this->createChartParam($share, $type['name'], $type['total']);
				$charts[0]->addChartData($type['total'], $param);	
			}

			$charts[] = KFactory::get('admin::com.ninjajournal.template.helper.fusionchart')->create('User stats', 'Users', 400, 300);
			$this->colors = $this->getColorscheme('#21561f', count($this->report['user']));
			foreach ($this->report['user'] as $usr)
			{				
				$share = $usr['total'] / $this->report['total'] >= 0.05;
				$param = $this->createChartParam($share, $usr['name'], $usr['total']);
				$charts[1]->addChartData($usr['total'], $param);
			}
			
			$this->charts = $charts;

		}
		elseif ($this->state->user)
		{
			// create report
			$this->report = $model->sort('created_on')->getUserReport();
			$this->layout = 'report_user';

			// create chart
			$this->chart = KFactory::get('admin::com.ninjajournal.template.helper.fusionchart')->create();
			
			$this->colors = $this->getColorscheme('#FC7000', count($this->report['project']));
			foreach ($this->report['project'] as $project)
			{
				$share = $project['total'] / $this->report['total'] >= 0.05;
				$param = $this->createChartParam($share, $project['name'], $project['total']);
				$this->chart->addChartData($project['total'], $param);
			}
		}
		else
		{
			KFactory::get('admin::com.ninja.helper.graphael');
			
			// create report
			$this->report = $model->sort('duration')->direction('desc')->getPeriodReport();
			$this->layout = 'report_period';

			// create chart
			$this->chart = KFactory::get('admin::com.ninjajournal.template.helper.fusionchart')->create();

			$this->colors = $this->getColorscheme('#6B007F', count($this->report['data']));
			foreach ($this->report['data'] as $project)
			{
				$share = $project['duration'] / $this->report['total'] >= 0.05;
				$param = $this->createChartParam($share, $project['name'], $project['duration']);
				$this->chart->addChartData($project['duration'], $param);	
			}
		}

		// set template vars
		$this->assign('lists', $lists);
		$this->assign('date_presets', $date_presets);

		return parent::display();
	}

	/**
	 * Creates a param string ready to use with $chart->addChartData
	 *
	 * @author Stian Didriksen <stian@ninjaforge.com>
	 * @param  $share		boolean
	 * @param  $name		string
	 * @param  $hoverText	string
	 * @param  $color		hex string
	 * @return string
	 */
	private function createChartParam($share, $name, $hoverText)
	{
		$hoverText = $name.' (' . ComNinjajournalTemplateHelperDate::formatTimespan(array('time' => $hoverText, 'format' => 'hr mi')).')';
		$color     = array_shift($this->colors);
		if(!$share) $name = null; 
		return "name=$name;hoverText=$hoverText;color=$color;";
	}

	public function getColorscheme($color, $count) {
		preg_match("/^([\da-f]{2})([\da-f]{2})([\da-f]{2})$/i", preg_replace("/^#/", "", $color), $matches);
		$color_hex = array(strtolower($color));
		$color_rgb = array_slice($matches, 1);

		for ($i=0; $i < 3; $i++) {
			$color_rgb[$i] = hexdec($color_rgb[$i]);
		}
		
		for ($i=1; $i < $count; $i++) {
			$color_hex[$i] = "#";
			for ($j=0; $j < 3; $j++) { 
				$color_rgb[$j] += 15;
				if ($color_rgb[$j] < 0)   $color_rgb[$j] = 0;
				if ($color_rgb[$j] > 255) $color_rgb[$j] = 255;
				$value_hex      = dechex($color_rgb[$j]);
				$color_hex[$i] .= strlen($value_hex) < 2 ? "0".$value_hex : $value_hex;
			}
		}

		return $color_hex;
	}
}