<?php
/**
* @version		$Id: jvrelatives.php 112 2011-06-13 18:52:28Z sniranjan $
* @package		JV-Relatives
* @subpackage	com_jvrelatives
* @copyright	Copyright 2008-2013 JV-Extensions. All rights reserved
* @license		GNU General Public License version 3 or later
* @author		JV-Extensions
* @link			http://www.jv-extensions.com
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

if (!JFactory::getUser()->authorise('core.manage', 'com_jvrelatives'))
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

require_once(JPATH_COMPONENT.DS.'common'.DS.'class.init.php');

JLoader::register('JvrelativesHelper', dirname(__FILE__).DS.'helpers'.DS.'jvrelatives.php');
JLoader::register('JvrelInstallerHelper', dirname(__FILE__).DS.'helpers'.DS.'class.installer.php');
JLoader::register('JvrelVersionInstaller', dirname(__FILE__).DS.'helpers'.DS.'class.installer.php');

jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('Jvrelatives');

$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();