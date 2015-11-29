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

class JvrelativesViewJvrelatives extends JViewLegacy
{
    protected $installed_version;
    protected $news_feed = array();

    function display($tpl = null)
    {
        $this->installed_version = JvrelInstallerHelper::getVersionFromManifest();
        $this->news_feed = $this->get('LatestNews');

        $this->addToolBar();
        $this->sidebar = JHtmlSidebar::render();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar()
    {
        JToolBarHelper::title(JText::_('COM_JVRELATIVES_ADMIN'), 'jvrelatives');

        $canDo = JvrelativesHelper::getActions();
        if ($canDo->get('core.admin'))
            JToolBarHelper::preferences('com_jvrelatives');
    }

    protected function setDocument()
    {
        JHtml::_('jquery.framework');
        
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_JVRELATIVES_ADMIN'));
        $document->addStyleSheet("components/com_jvrelatives/assets/css/jvrelatives.css");
        $document->addScriptDeclaration('
			jQuery(document).ready(function() {
				var url = "index.php?option=com_jvrelatives";
				jQuery("#latvers").click(function(event) {
					event.preventDefault();
					var ajaxPost = jQuery.post(url, {
						view : "ajax",
						format : "raw",
						layout : "getlatversion"
					});
		        
				 	ajaxPost.done(function(data) {
						jQuery("#updatearea").empty().html(data);
					})
					ajaxPost.fail(function() {
						alert("Error encountered");
					})
					ajaxPost.always(function() {
					});
				});
			});
        		
			jQuery(document).ready(function() {
				var url = "index.php?option=com_jvrelatives";
				var ajaxPost = jQuery.post(url, {
					view : "ajax",
					format : "raw",
					layout : "getlatestnews"
				});
					        
				ajaxPost.done(function(data) {
					jQuery("#newsarea").empty().html(data);
				})
				ajaxPost.fail(function() {
				})
				ajaxPost.always(function() {
				});
			});
		');        
    }
}