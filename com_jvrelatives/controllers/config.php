<?php
/**
 * @version		$Id: config.php 112 2011-06-13 18:52:28Z sniranjan $
 * @package		JV-Relatives
 * @subpackage	com_jvrelatives
 * @copyright	Copyright 2008-2013 JV-Extensions. All rights reserved
 * @license		GNU General Public License version 3 or later
 * @author		JV-Extensions
 * @link		http://www.jv-extensions.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controllerform');

class JvrelativesControllerConfig extends JControllerForm
{
	public function edit($key = null, $urlVar = null)
	{
		$canDo = JvrelativesHelper::getActions();
		if (!$canDo->get('core.admin'))
		{
        	$this->setMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
        	$this->setRedirect(JRoute::_('index.php?option=com_jvrelatives&view=jvrelatives', false));
			return false;
		}

		return parent::edit($key, $urlVar);
	}
}