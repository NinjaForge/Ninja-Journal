<?php
/**
 * @version		$Id: select.php 490 2011-01-20 00:22:00Z stian $
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * View helper for creating different select lists
 */
class ComNinjajournalTemplateHelperSelect extends KTemplateHelperAbstract
{
	public function state($config = array())// $enabled = 0 )
	{
		$config = new KConfig($config);
		
		$config->append(array(
			'state' => false
		));
		
		//@TODO lazy lazy lazy
		$enabled = KConfig::toData($config->state);
	
		static $instances;
	
		if(!$instances) $instances = 0;
	
		$options = array();
		$options[] = JHTML::_('select.option',  '0', JText::_( 'Open' ));
		if(KInflector::pluralize(KRequest::get('get.view', 'cmd')) != 'todos')
		{
			$options[] = JHTML::_('select.option',  1, JText::_( 'Closed'));
			$rel = "{'toggle':$enabled,'state':['open','close']}";
		}
		else
		{
			$options[] = JHTML::_('select.option',  1, JText::_( 'Done'));
			$options[] = JHTML::_('select.option',  2, JText::_( 'Closed'));
			$rel = "{'toggle':$enabled,'state':['open','done','close']}";
		}

		if(KInflector::isPlural(KRequest::get('get.view', 'cmd')))
		{
			if($instances++ < 1)
			{
				return JHTML::_('select.genericlist', $options, 'state', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', $enabled );
			}
			else
			{
				ob_start();
			?>
			(function($){
				window.addEvent('domready', function(){
					$$('select.toggle-states').addEvent('change', function(){
						var target = this;
						if(target.retrieve('busy')) return;
						if(target.hasClass('toggle-states'))
						{
							var options = target.retrieve('options') || Json.evaluate(target.get('rel')),
								toggle  = this.get('value'),
								data	= {
									id: target.getParent().getParent().getElement('input.id').get('value'),
									action: options.state[toggle],
									_token: this.form.getElement('[name=_token]').get('value')
								};
							
							if(options.edit) data[options.edit] = target.get('value');
			
							target.store('busy', true);
							new Request.JSON({
								url: this.form.get('action')+'&format=json',
								data: data,
								onComplete: function(){
																		
									target.getParent().getParent().swapClass('state-' + options.state[options.toggle], 'state-' + options.state[toggle]);
									target.store('options', options).eliminate('busy');
								},
								onSuccess: function(response){
									if(!response.msg) return;
									if(typeof Roar == 'function') new Roar().alert(response.msg);
								}
							}).post();
						}
					});
				});
			})(document.id);
			<?php
				KFactory::get('admin::com.ninja.helper.default')->js(ob_get_clean());
				//unset($options[0]);
				return JHTML::_('select.genericlist', $options, 'state', 'class="inputbox toggle-states" rel="'.$rel.'" size="1" style="position: relative; top: 2px; right: 2px;"', 'value', 'text', $enabled );
			}
		}

		return JHTML::_('select.genericlist', $options, 'state', 'class="inputbox value required"', 'value', 'text', $enabled );		
	}
	
	public function projects($config = array())//$selected, $name = 'ninjajournal_project_id', $attribs = array('class' => 'inputbox value required'), $idtag = 'project', $allowAny = true)
 	{
		$config = new KConfig($config);

		$config->append(array(
			'state' => array(
				'project' => false
			)
 		))->append(array(
 			'selected'	=> $config->state->project,
 			'name'		=> 'ninjajournal_project_id',
 			'attribs'	=> array(
 				'class'	=> 'inputbox value required',
 				'id'	=> 'project'
 			),
 			'allowAny'	=> true,
 			'items'		=> KFactory::get('admin::com.ninjajournal.model.projects')->getList()
 		));

		// Add first option to list
        $list = array();
		if($config->allowAny) {
			if(KRequest::get('get.view', 'cmd') == 'reports')
			{
				$list[] =  JHTML::_('select.option', null, '- '.JText::_( 'All Projects' ).' -', 'id', 'title' );
			}
			elseif(KInflector::pluralize(KRequest::get('get.view', 'cmd')) != 'logs')
			{
				$list[] =  JHTML::_('select.option', '0', '- '.JText::_( 'Global (All Projects)' ).' -', 'id', 'title' );
			}
			else
			{
				$list[] =  JHTML::_('select.option', '0', '- '.JText::_( 'Select Project' ).' -', 'id', 'title' );
			}
			
		}
		
		if(KInflector::isPlural(KRequest::get('get.view', 'cmd')))
		{
			$config->attribs->onchange = 'this.form.submit()';
			$config->name = 'project';
		}

		// Marge first option with departments
		$list = array_merge( $list, $config->items->getData());

		// build the HTML list
		return JHTML::_('select.genericlist', $list, $config->name, $config->attribs->toArray(), 'id', 'title', $config->selected, $config->attribs->id );
 	}
 	
 	public function types($config = array())//$selected, $name = 'ninjajournal_type_id', $attribs = array('class' => 'inputbox value required'), $idtag = 'type', $allowAny = true)
 	{
 		$config = new KConfig($config);
 		
		$config->append(array(
			'state' => array(
				'type' => false
			)
		))->append(array(
			'selected'	=> $config->state->type,
			'name'		=> 'ninjajournal_type_id',
			'attribs'	=> array(
				'class'	=> 'inputbox value required',
				'id'	=> 'type'
			),
			'allowAny'	=> true,
			'items'		=> KFactory::get('admin::com.ninjajournal.model.types')->getList()
		));

		// Add first option to list
        $list = array();
		if($config->allowAny) {
			$list[] =  JHTML::_('select.option', '', '- '.JText::_( 'Select Type' ).' -', 'id', 'title' );
		}

		// Marge first option with departments
		$list = array_merge( $list, $config->items->getData());

		// build the HTML list
		return JHTML::_('select.genericlist', $list, $config->name, $config->attribs->toArray(), 'id', 'title', $config->selected, $config->attribs->id );
 	}
 	
    public function users($config = array())//$selected, $name = 'user', $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'this.form.submit()'), $idtag = null, $allowAny = false)
    {
		$config = new KConfig($config);

		$config->append(array(
			'state' => array(
				'user' => false
			)
		))->append(array(
			'selected'	=> $config->state->user,
			'name'		=> 'user',
			'attribs'	=> array(
				'class'		=> 'inputbox value required',
				'size'		=> 1,
				'onchange'	=> 'this.form.submit()',
				'id'		=> 'user'
			),
			'allowAny'	=> true,
			'items'		=> KFactory::get('admin::com.ninjajournal.model.users')->limit(10)->productive(true)->getList()->getData()
		));
	
		// Add first option to list
		$list[] = JHTML::_('select.option', '', JText::_( '- Select User -' ), 'id', 'name' );

        $list = array_merge( $list, KConfig::toData($config->items) );

        // build the HTML list
        return JHTML::_('select.genericlist',  $list, $config->name, $config->attribs->toArray(), 'id', 'name', $config->selected, $config->attribs->id );
    }
}