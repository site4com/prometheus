<?php
/**
 * @version		$Id: edit.php 112 2011-06-13 18:52:28Z sniranjan $
 * @package		JV-Relatives
 * @subpackage	com_jvrelatives
 * @copyright	Copyright 2008-2013 JV-Extensions. All rights reserved
 * @license		GNU General Public License version 3 or later
 * @author		JV-Extensions
 * @link		http://www.jv-extensions.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<?php if (!empty($this->sidebar)) : ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif;?>

	<form action="<?php echo JRoute::_('index.php?option=com_jvrelatives&layout=edit&id='.(int)$this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#common_relart" data-toggle="tab"><?php echo JText::_('COM_JVRELATIVES_FIELDSET_COMMON');?></a></li>
						<li><a href="#eopsob" data-toggle="tab"><?php echo JText::_('COM_JVRELATIVES_FIELDSET_EOPSOB');?></a></li>
						<li><a href="#advanced" data-toggle="tab"><?php echo JText::_('COM_JVRELATIVES_FIELDSET_ADVANCED');?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="common_relart">
							<?php echo $this->loadTemplate('common_relart'); ?>
						</div>
						<div class="tab-pane" id="eopsob">
							<?php echo $this->loadTemplate('eopsob'); ?>
						</div>
						<div class="tab-pane" id="advanced">
							<?php echo $this->loadTemplate('advanced'); ?>
						</div>
					</div>
				</fieldset>

				<input type="hidden" name="task" value="" />
				<?php echo JHtml::_('form.token'); ?>

			</div>
		</div>
	</form>

	<?php JvrelativesHelper::showFooter(); ?>
</div>