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

jimport('joomla.application.component.controller');

class JvrelativesControllerLog extends JControllerLegacy
{
	function display($cachable = false, $urlparams = false)
	{
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jvrelatives.php');

		JvrelativesHelper::addSubmenu(JFactory::getApplication()->input->getCmd('view', 'log'));
		parent::display($cachable, $urlparams);

		return $this;
	}

	function clear()
	{
		if (JFile::exists(_JVREL_LOGPATH.DS."com_jvrelatives.log.php"))
			JFile::delete(_JVREL_LOGPATH.DS."com_jvrelatives.log.php");

		$this->setMessage(JText::_("COM_JVRELATIVES_LOG_CLEARED"));
		$this->setRedirect(JRoute::_('index.php?option=com_jvrelatives&view=log', false));
		return;
	}
}