<?php
/**
 * @version		$Id: controller.php 112 2011-06-13 18:52:28Z sniranjan $
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

class JvrelativesController extends JControllerLegacy
{
    function display($cachable = false, $urlparams = false)
    {
    	require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jvrelatives.php');

        $view = JFactory::getApplication()->input->getCmd('view', 'jvrelatives');
        $layout = JFactory::getApplication()->input->getCmd('layout', 'default');
        $id	= JFactory::getApplication()->input->getInt('id');

        // Check for edit form.
        if ($view == 'config' && $layout == 'edit' && !$this->checkEditId('com_jvrelatives.edit.config', $id)) {
        	// Somehow the person just went to the form - we don't allow that.
        	$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
        	$this->setMessage($this->getError(), 'error');
        	$this->setRedirect(JRoute::_('index.php?option=com_jvrelatives&view=jvrelatives', false));

        	return false;
        }

        // route configs view back to dashboard
        if ($view == 'configs') {
			$this->setRedirect(JRoute::_('index.php?option=com_jvrelatives&view=jvrelatives', false));
			return;
        }

        JvrelativesHelper::addSubmenu($view);

        parent::display($cachable, $urlparams);
        return $this;
    }

	function clearcache()
    {
    	$cache_folder = JPATH_ADMINISTRATOR.DS."components".DS."com_jvrelatives".DS."cache";

    	try
    	{
    		if (JFolder::delete($cache_folder) == false)
    			throw new Exception(JText::_("COM_JVRELATIVES_CLEARCACHE_ERROR"));

    		if (JFolder::create($cache_folder) == false)
    			throw new Exception(JText::_("COM_JVRELATIVES_CLEARCACHE_ERROR"));

    		if (JFile::copy(JPATH_ADMINISTRATOR.DS."components".DS."com_jvrelatives".DS."index.html", $cache_folder.DS."index.html") == false)
    			throw new Exception(JText::_("COM_JVRELATIVES_CLEARCACHE_ERROR"));

    		$this->setMessage(JText::_("COM_JVRELATIVES_CLEARCACHE_OK"));
    	}
    	catch (Exception $ex)
    	{
    		$this->setMessage($ex->getMessage(), 'error');
    	}

    	$this->setRedirect(JRoute::_('index.php?option=com_jvrelatives&view=jvrelatives', false));
    	return;
    }


    function delthumbs()
    {
    	try
    	{
    		if (JFolder::delete(_JVREL_ABS_THUMBPATH) == false)
    			throw new Exception(JText::_("COM_JVRELATIVES_CLEARCACHE_ERROR"));

    		if (JFolder::create(_JVREL_ABS_THUMBPATH) == false)
    			throw new Exception(JText::_("COM_JVRELATIVES_CLEARCACHE_ERROR"));

    		if (JFile::copy(JPATH_ADMINISTRATOR.DS."components".DS."com_jvrelatives".DS."index.html", _JVREL_ABS_THUMBPATH.DS."index.html") == false)
    			throw new Exception(JText::_("COM_JVRELATIVES_CLEARCACHE_ERROR"));

    		$this->setMessage(JText::_("COM_JVRELATIVES_CLEARCACHE_OK"));
    	}
    	catch (Exception $ex)
    	{
    		$this->setMessage($ex->getMessage(), 'error');
    	}

    	$this->setRedirect(JRoute::_('index.php?option=com_jvrelatives&view=jvrelatives', false));
    	return;
    }
}