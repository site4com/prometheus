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

<div class="row-fluid">
	<div class="span12">
		<div class="tabbable tabs-left">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#common_display" data-toggle="tab"><?php echo JText::_('COM_JVRELATIVES_FIELDSET_COMMON_DISPLAY');?></a></li>
				<li><a href="#common_general" data-toggle="tab"><?php echo JText::_('COM_JVRELATIVES_FIELDSET_COMMON_GENERAL');?></a></li>
				<li><a href="#common_thumbnail" data-toggle="tab"><?php echo JText::_('COM_JVRELATIVES_FIELDSET_COMMON_THUMBNAIL');?></a></li>
				<li><a href="#common_tags" data-toggle="tab"><?php echo JText::_('COM_JVRELATIVES_FIELDSET_TAGS');?></a></li>
				<li><a href="#common_smedia" data-toggle="tab"><?php echo JText::_('COM_JVRELATIVES_FIELDSET_SMEDIA');?></a></li>
			</ul>
			<div class="tab-content">
				<div class="well well-small"><?php echo JText::_("COM_JVRELATIVES_FIELDSET_COMMON_DESC"); ?></div>
				<div class="tab-pane active" id="common_display">
					<div class="control-group">
        				<div class="control-label"><?php echo $this->form->getLabel('style'); ?></div>
            			<div class="controls"><?php echo $this->form->getInput('style'); ?></div>
        			</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('show_title_charcnt'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('show_title_charcnt'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('show_intro'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('show_intro'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('show_intro_charcnt'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('show_intro_charcnt'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('expand_title_show_intro'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('expand_title_show_intro'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('show_timestamp_info'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('show_timestamp_info'); ?></div>
					</div>
			    	<div class="control-group">
			        	<div class="control-label"><?php echo $this->form->getLabel('timestamp_format'); ?></div>
			            <div class="controls"><?php echo $this->form->getInput('timestamp_format'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('show_hits'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('show_hits'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('show_creator'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('show_creator'); ?></div>
					</div>
				</div>
				<div class="tab-pane" id="common_general">
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('new_window'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('new_window'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('sort_criteria'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('sort_criteria'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('no_relatives_text'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('no_relatives_text'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('no_relatives_text_show'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('no_relatives_text_show'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('no_metakeys_text'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('no_metakeys_text'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('no_metakeys_text_show'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('no_metakeys_text_show'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('ignore_keywords'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('ignore_keywords'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('only_from_author'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('only_from_author'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('recent_days'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('recent_days'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('exclude_urls'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('exclude_urls'); ?></div>
					</div>
				</div>
				<div class="tab-pane" id="common_thumbnail">
					<div class="well well-small"><?php echo JText::_("COM_JVRELATIVES_FIELDSET_COMMON_THUMBNAIL_DESC"); ?></div>
					<div class="control-group">
			        	<div class="control-label"><?php echo $this->form->getLabel('link_thumbnail'); ?></div>
			            <div class="controls"><?php echo $this->form->getInput('link_thumbnail'); ?></div>
			        </div>
			        <div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('default_thumbnail'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('default_thumbnail'); ?></div>
					</div>
				</div>
				<div class="tab-pane" id="common_tags">
					<div class="well well-small"><?php echo JText::_("COM_JVRELATIVES_FIELDSET_TAGS_DESC"); ?></div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('tag_link_search'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('tag_link_search'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('tag_link_search_wintarget'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('tag_link_search_wintarget'); ?></div>
					</div>
				</div>
				<div class="tab-pane" id="common_smedia">
					<div class="well well-small"><?php echo JText::_("COM_JVRELATIVES_FIELDSET_SMEDIA_DESC"); ?></div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('social_code'); ?></div>
						<div class="controls">
							<?php echo $this->form->getInput('social_code'); ?><br />
							<p><small><?php echo JText::_('COM_JVRELATIVES_LABEL_SOCIAL_CODE_DESC'); ?></small></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>