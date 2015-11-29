<?php
/**
 * @version		$Id: view.html.php 112 2011-06-13 18:52:28Z sniranjan $
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

class JvrelativesViewConfig extends JViewLegacy
{
	protected $form;
	protected $item;

    function display($tpl = null)
    {
    	$this->form = $this->get('Form');
        $this->item = $this->get('Item');

		if (count($errors = $this->get('Errors')))
        {
			JError::raiseError(500, implode('\n', $errors));
            return false;
		}

        $this->addToolBar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar()
    {
    	JFactory::getApplication()->input->set('hidemainmenu', true);

        JToolBarHelper::title(JText::_('COM_JVRELATIVES_CONFIGURATION_EDIT'), 'jvrelatives');
        JToolBarHelper::apply('config.apply', 'JTOOLBAR_APPLY');
        JToolBarHelper::save('config.save', 'JTOOLBAR_SAVE');
		JToolBarHelper::cancel('config.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_JVRELATIVES_CONFIGURATION'));
        $document->addStyleSheet("components/com_jvrelatives/assets/css/jvrelatives.css");
    }
}