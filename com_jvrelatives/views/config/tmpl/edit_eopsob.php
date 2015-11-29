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
?>

<div class="well well-small"><?php echo JText::_("COM_JVRELATIVES_FIELDSET_EOPSOB_DESC"); ?></div>

<div class="control-group">
	<div class="control-label"><?php echo $this->form->getLabel('eopsob_en'); ?></div>
	<div class="controls"><?php echo $this->form->getInput('eopsob_en'); ?></div>
</div>
<div class="control-group">
	<div class="control-label"><?php echo $this->form->getLabel('eopsob_num_articles'); ?></div>
	<div class="controls"><?php echo $this->form->getInput('eopsob_num_articles'); ?></div>
</div>
<div class="control-group">
	<div class="control-label"><?php echo $this->form->getLabel('eopsob_show_article_image'); ?></div>
	<div class="controls"><?php echo $this->form->getInput('eopsob_show_article_image'); ?></div>
</div>
<div class="control-group">
	<div class="control-label"><?php echo $this->form->getLabel('eopsob_show_tags'); ?></div>
	<div class="controls"><?php echo $this->form->getInput('eopsob_show_tags'); ?></div>
</div>
<div class="control-group">
	<div class="control-label"><?php echo $this->form->getLabel('eopsob_show_smedia'); ?></div>
	<div class="controls"><?php echo $this->form->getInput('eopsob_show_smedia'); ?></div>
</div>
<div class="control-group">
	<div class="control-label"><?php echo $this->form->getLabel('eopsob_box_width'); ?></div>
	<div class="controls"><?php echo $this->form->getInput('eopsob_box_width'); ?></div>
</div>
<div class="control-group">
	<div class="control-label"><?php echo $this->form->getLabel('eopsob_only'); ?></div>
	<div class="controls"><?php echo $this->form->getInput('eopsob_only'); ?></div>
</div>