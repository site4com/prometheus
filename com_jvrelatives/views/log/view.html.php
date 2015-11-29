<?php
/**
 * @version		$Id$
 * @package		JV-Relatives
 * @subpackage	com_jvrelatives
 * @copyright	Copyright 2008-2013 JV-Extensions. All rights reserved
 * @license		GNU General Public License version 3 or later
 * @author		JV-Extensions
 * @link		http://www.jv-extensions.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JvrelativesViewLog extends JViewLegacy
{
	protected $dbginfo;

    function display($tpl = null)
    {
    	$this->dbginfo = '';

    	try
    	{
    		if (JFile::exists(_JVREL_LOGPATH.DS.'com_jvrelatives.log.php'))
    			$this->dbginfo = file_get_contents(_JVREL_LOGPATH.DS.'com_jvrelatives.log.php');
    	}
    	catch (Exception $ex)
    	{
    		JError::raiseWarning(500, $ex->getMessage());
    	}

        $this->addToolBar();
        $this->sidebar = JHtmlSidebar::render();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar()
    {
        JToolBarHelper::title(JText::_('COM_JVRELATIVES_ADMIN') . " - ". JText::_("COM_JVRELATIVES_LOG"), 'jvrelatives');

        $canDo = JvrelativesHelper::getActions();
        if ($canDo->get('core.admin'))
            JToolBarHelper::preferences('com_jvrelatives');
    }

    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_JVRELATIVES_ADMIN') . " - ". JText::_("COM_JVRELATIVES_LOG"));
        $document->addStyleSheet('components/com_jvrelatives/assets/css/jvrelatives.css');
    }
}