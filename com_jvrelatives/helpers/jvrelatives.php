<?php
/**
 * @version		$Id: jvrelatives.php 112 2011-06-13 18:52:28Z sniranjan $
 * @package		JV-Relatives
 * @subpackage	com_jvrelatives
 * @copyright	Copyright 2008-2013 JV-Extensions. All rights reserved
 * @license		GNU General Public License version 3 or later
 * @author		JV-Extensions
 * @link		http://www.jv-extensions.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

abstract class JvrelativesHelper
{
    public static function addSubmenu($in_view = 'jvrelatives')
    {
    	$canDo = JvrelativesHelper::getActions();

        JHtmlSidebar::addEntry(JText::_('COM_JVRELATIVES_DASHBOARD'), 'index.php?option=com_jvrelatives&view=jvrelatives', $in_view == 'jvrelatives');

    	if ($canDo->get('core.admin'))
        	JHtmlSidebar::addEntry(JText::_('COM_JVRELATIVES_CONFIGURATION'), 'index.php?option=com_jvrelatives&task=config.edit&id=1', $in_view == 'config');

    	JHtmlSidebar::addEntry(JText::_('COM_JVRELATIVES_LOG'), 'index.php?option=com_jvrelatives&view=log', $in_view == 'log');
        JHtmlSidebar::addEntry(JText::_('COM_JVRELATIVES_CLEARCACHE'), 'index.php?option=com_jvrelatives&task=clearcache', false);
        JHtmlSidebar::addEntry(JText::_('COM_JVRELATIVES_DELTHUMBS'), 'index.php?option=com_jvrelatives&task=delthumbs', false);

        $document = JFactory::getDocument();
        $document->addStyleDeclaration('.icon-48-jvrelatives {background-image: url(../media/com_jvrelatives/assets/images/icon-48x48.png);}');

        switch ($in_view)
        {
            case 'config': $title = JText::_('COM_JVRELATIVES_CONFIGURATION'); break;
            case 'log': $title = JText::_('COM_JVRELATIVES_LOG'); break;
            default: $title = JText::_('COM_JVRELATIVES_ADMIN'); break;
        }

        $document->setTitle($title);
    }

    public static function getActions()
    {
        $user = JFactory::getUser();
        $result = new JObject;

        $actions = array('core.admin', 'core.manage');
        foreach ($actions as $action)
            $result->set($action, $user->authorise($action, 'com_jvrelatives'));

        return $result;
    }

    public static function showFooter()
    {
    	$val = '<p>&nbsp;</p>
    			<div class="well well-small">
					<strong>JV-Relatives '.JvrelInstallerHelper::getDbVersion().'</strong><br />
					Copyright 2008-2015. Niranjan SrinivasaRao / JV-Extensions.com<br />
					<strong>If you like JV-Relatives, please post a rating and a review at the <a href="http://extensions.joomla.org/extensions/news-display/articles-display/related-items/4500" target="_blank">Joomla! Extensions Directory</a>.</strong>
				</div>';
    	echo $val;
    }
}