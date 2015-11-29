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

class JvrelativesViewInstall extends JViewLegacy
{
	protected $desc;
	protected $versions_to_upgrade = array();
	protected $compname = 'JV-Relatives';
	protected $compcode = 'com_jvrelatives';

	function display($tpl = null)
	{
		JHtml::_('jquery.framework');
		
		$title = "";
		$versions_to_upgrade = array();

		try
		{
			$ma_version = JvrelInstallerHelper::getVersionFromManifest();
			$db_version = JvrelInstallerHelper::getDbVersion();

			if (0 == $db_version)
			{
				$title = $this->compname." Component - New Installation";
				$desc = "Welcome to New Installation of ".$this->compname." Component. This utility will install ".$this->compname." component version ".$ma_version." on your Joomla website. Please click on the 'Install/Upgrade' button to start the installation";
			}
			else
			{
				$sindex = $tindex = 0;
				$versions_to_upgrade = JvrelInstallerHelper::getVersionsUpgradeList($db_version, $sindex, $tindex);
				$title = $this->compname." - Upgrade";
				$desc = "Welcome to Upgrade Installation of ".$this->compname." Component. This utility will upgrade the ".$this->compname." component to version ".$ma_version." on your Joomla website. Please click on the 'Install/Upgrade' button to start the upgrade";
			}
			
			$ajaxQuery = '	jQuery(document).ready(function() {
								var url = "index.php?option='.$this->compcode.'";
								jQuery("#install").click(function(event) {
									event.preventDefault();	
									jQuery("#install").prop("disabled", true);
										
									var ajaxPost = jQuery.post(url, {
										view : "ajax",
										format : "raw",
										layout : "install"
									});
									ajaxPost.done(function(data) {
										var parsed = data.split("|");
										var nstep = parsed[0];
										var errflag = parsed[1];
										var percent = parsed[2];
            							var msg = parsed[3];
										
										jQuery("#iarea").append("<br />"+msg+"<br /><br />");
										jQuery("#pbar").css("width", percent+"%");
										
										if (nstep == "0") {
											if (errflag == "0") {
												jQuery("#pbar").addClass("bar-success");
												jQuery("#dashboard").css("display", "inline");																				
												jQuery("#iarea").append("<div class=\"alert alert-success\">All installation/upgrade steps have been completed successfully</div>");										
											}
											else {
												jQuery("#pbar").addClass("bar-danger");
												jQuery("#iarea").append("<div class=\"alert alert-error\">Error encountered during installation/upgrade. Please contact support@jv-extensions.com</div>");
											}
										}
										else {
											jQuery("tr").removeClass("success");
											jQuery("#"+nstep).addClass("success");
										
											timer = setTimeout(function() {
												jQuery("#install").trigger("click");
            								}, 2000);  											
										}
									})				
									ajaxPost.fail(function() {
										jQuery("#pbar").css("width", "100%");
										jQuery("#pbar").addClass("bar-danger");
										jQuery("#iarea").append("<div class=\"alert alert-error\">Error encountered during installation/upgrade</div>");										
									})
									ajaxPost.always(function() {
									});						
								});	
							});
						';

   			$document = JFactory::getDocument();
   			$document->addScriptDeclaration($ajaxQuery);

			$this->desc = $desc;
		}
		catch (Exception $ex)
		{
			$title = $this->compname." Installation";
			$this->desc = $ex->getMessage();
		}

		$this->versions_to_upgrade = $versions_to_upgrade;

		JToolBarHelper::title($title, 'generic.png');
		parent::display($tpl);
	}
}

