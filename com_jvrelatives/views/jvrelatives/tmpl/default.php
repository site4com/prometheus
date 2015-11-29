<?php
/**
 * @version		$Id: default.php 128 2011-07-09 09:25:34Z sniranjan $
 * @package		JV-Relatives
 * @subpackage	com_jvrelatives
 * @copyright	Copyright 2008-2013 JV-Extensions. All rights reserved
 * @license		GNU General Public License version 3 or later
 * @author		JV-Extensions
 * @link		http://www.jv-extensions.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
?>

<?php if (!empty($this->sidebar)) : ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif;?>

	<div class="row-fluid">
		<div class="span7">
			<div class="row-fluid center">
				<a href="http://www.jv-extensions.com" target="_blank"><img src="<?php echo JUri::root(); ?>media/com_jvrelatives/assets/images/jvextensions.png" align="middle" alt="JV-Relatives from JV-Extensions" style="border: none; margin: 8px;" /></a>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<h3><?php echo JText::_("COM_JVRELATIVES_GET_STARTED"); ?></h3>
					<div class="well well-small">
						<ul>
							<li>
								<?php echo JText::_("COM_JVRELATIVES_WORKING_DESC_1"); ?>
								<ul>
									<li><?php echo JText::_("COM_JVRELATIVES_WORKING_DESC_11"); ?></li>
									<li><?php echo JText::_("COM_JVRELATIVES_WORKING_DESC_12"); ?></li>
								</ul>
							</li>
							<li><?php echo JText::_("COM_JVRELATIVES_WORKING_DESC_2"); ?></li>
							<li><?php echo JText::_("COM_JVRELATIVES_WORKING_DESC_3"); ?></li>
						</ul>
						<table class="table table-hover table-condensed">
						<tbody>
							<tr>
					            <td width="20%"><p class="muted"><?php echo JText::_("COM_JVRELATIVES_GET_STARTED_STEP1"); ?>:</p></td>
					            <td width="80%"><?php echo JText::_("COM_JVRELATIVES_GET_STARTED_STEP1_DESC"); ?></td>
					        </tr>
							<tr>
					            <td><p class="muted"><?php echo JText::_("COM_JVRELATIVES_GET_STARTED_STEP2"); ?>:</p></td>
					            <td><?php echo JText::_("COM_JVRELATIVES_GET_STARTED_STEP2_DESC"); ?></td>
					        </tr>
					        <tr>
					            <td><p class="muted"><?php echo JText::_("COM_JVRELATIVES_GET_STARTED_STEP3"); ?>:</p></td>
					            <td><?php echo JText::_("COM_JVRELATIVES_GET_STARTED_STEP3_DESC"); ?></td>
					        </tr>
					        <tr>
					            <td><p class="muted"><?php echo JText::_("COM_JVRELATIVES_GET_STARTED_STEP4"); ?>:</p></td>
					            <td><?php echo JText::_("COM_JVRELATIVES_GET_STARTED_STEP4_DESC"); ?></td>
					        </tr>
					        <tr>
					            <td><p class="muted"><?php echo JText::_("COM_JVRELATIVES_GET_STARTED_STEP5"); ?>:</p></td>
					            <td><?php echo JText::_("COM_JVRELATIVES_GET_STARTED_STEP5_DESC"); ?></td>
					        </tr>
					        </tbody>
					    </table>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<h3><?php echo JText::_("COM_JVRELATIVES_LINKS_TO_JVEXTENSIONS"); ?></h3>
					<ul class="nav nav-list">
						<li><a href="http://www.jv-extensions.com/my-account/my-downloads" target="_blank"><i class="icon-download"></i> <?php echo JText::_('COM_JVRELATIVES_LABEL_DOWNLOADS'); ?></a></li>
						<li><a href="http://www.jv-extensions.com/documentation/jv-relatives" target="_blank"><i class="icon-briefcase"></i> <?php echo JText::_('COM_JVRELATIVES_LABEL_DOCUMENTATION'); ?></a></li>
						<li><a href="http://www.jv-extensions.com/support/support-tickets" target="_blank"><i class="icon-user"></i> <?php echo JText::_('COM_JVRELATIVES_LABEL_SUPPORT'); ?></a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="span5 pull-right">
			<h3><?php echo JText::_("COM_JVRELATIVES_INFO"); ?></h3>
			<table class="table table-striped">
			<tbody>
				<tr>
		            <td width="20%"><?php echo JText::_("COM_JVRELATIVES_INSTALLED_VERSION"); ?>:</td>
		            <td width="80%"><?php echo $this->installed_version; ?></td>
		        </tr>
		        <tr>
		            <td><?php echo JText::_("COM_JVRELATIVES_LATEST_VERSION"); ?>:</td>
		            <td><span id="updatearea"><a id="latvers" href="#"><?php echo JText::_("COM_JVRELATIVES_CHECK_LATEST_VERSION"); ?></a></span></td>
		        </tr>
		        <tr>
		            <td><?php echo JText::_("COM_JVRELATIVES_COPYRIGHT"); ?>:</td>
		             <td>&copy; 2008 - 2014, JV-Extensions</td>
		        </tr>
		        <tr>
		            <td><?php echo JText::_("COM_JVRELATIVES_AUTHOR"); ?>:</td>
		            <td><a href="http://www.jv-extensions.com" target="_blank">JV-Extensions</a>, <a href="mailto:sales@jv-extensions.com">sales@jv-extensions.com</a></td>
		        </tr>
		        <tr>
		            <td><?php echo JText::_("COM_JVRELATIVES_DESCRIPTION"); ?>:</td>
		            <td><?php echo JText::_("COM_JVRELATIVES_DESCVAL"); ?></td>
		        </tr>
		        <tr>
		            <td><?php echo JText::_("COM_JVRELATIVES_LICENSE"); ?>:</td>
		            <td>GNU GPLv3 - <a href="#license" role="button" class="btn" data-toggle="modal"><?php echo JText::_("COM_JVRELATIVES_READLICENSE"); ?></a></td>
		        </tr>
			</tbody>
			</table>

			<h3><?php echo JText::_('COM_JVRELATIVES_LABEL_NEWSFEED'); ?></h3>
        	<div id="newsarea">
				<p style="text-align:center;">
					<img src="<?php echo JUri::root(); ?>administrator/components/com_jvrelatives/assets/images/loading.GIF" />
				</p>
			</div>

			<h3><?php echo JText::_("COM_JVRELATIVES_IDEA"); ?></h3>
			<p style="text-align:center;">
                <a href="http://jv-extensions.uservoice.com" target="_blank">
                    <img src="<?php echo JUri::root(); ?>administrator/components/com_jvrelatives/assets/images/idea.png" style="width:100%;" />
                </a>
			</p>
		</div>
	</div>

	<?php JvrelativesHelper::showFooter(); ?>

</div>

<div id="license" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?php echo JText::_("COM_JVRELATIVES_READLICENSE"); ?></h3>
	</div>
	<div class="modal-body">
		<pre>
			<?php echo file_get_contents("../components/com_jvrelatives/LICENSE.txt"); ?>
		</pre>
	</div>
</div>