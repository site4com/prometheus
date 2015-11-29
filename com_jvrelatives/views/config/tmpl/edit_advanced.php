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

<div class="well well-small"><?php echo JText::_("COM_JVRELATIVES_FIELDSET_COMMON_DESC"); ?></div>

<div class="control-group">
	<div class="control-label"><?php echo $this->form->getLabel('load_bootstrap_css'); ?></div>
	<div class="controls"><?php echo $this->form->getInput('load_bootstrap_css'); ?></div>
</div>
<div class="control-group">
	<div class="control-label"><?php echo $this->form->getLabel('load_bootstrap_js'); ?></div>
	<div class="controls"><?php echo $this->form->getInput('load_bootstrap_js'); ?></div>
</div>
<div class="control-group">
	<div class="control-label"><?php echo $this->form->getLabel('poweredby'); ?></div>
	<div class="controls"><?php echo $this->form->getInput('poweredby'); ?></div>
</div>
<div class="control-group">
	<div class="control-label"><?php echo $this->form->getLabel('debug'); ?></div>
	<div class="controls"><?php echo $this->form->getInput('debug'); ?></div>
</div>
<div class="control-group">
	<div class="control-label"><?php echo $this->form->getLabel('cache'); ?></div>
	<div class="controls"><?php echo $this->form->getInput('cache'); ?></div>
</div>
<div class="control-group">
	<div class="control-label"><?php echo $this->form->getLabel('cache_lifetime'); ?></div>
	<div class="controls"><?php echo $this->form->getInput('cache_lifetime'); ?></div>
</div>