<?php 
/** 
 * @package JMAP::SOURCES::administrator::components::com_jmap
 * @subpackage views
 * @subpackage sources
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
?>
<div id="accordion_datasource_excludecats" class="sqlquerier panel panel-info panel-group adminform">
	<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#datasource_excludecats"><h4><?php echo JText::_('COM_JMAP_CATEGORIES_EXCLUSION' ); ?></h4></div>
	<div class="panel-body panel-collapse collapse" id="datasource_excludecats">
		<table  class="admintable">
			<tr>
				<td class="paramlist_key left_title">
					<span class="editlinktip"><label id="paramstitle-lbl" for="paramstitle" class="hasPopover" data-content="<?php echo JText::_('COM_JMAP_CHOOSE_CATEGORIES_EXCLUSION_DESC');?>"><?php echo JText::_('COM_JMAP_CHOOSE_CATEGORIES_EXCLUSION');?></label></span>
				</td>
				<td class="paramlist_value">
					<?php echo $this->lists['catexclusion']; ?>
				</td>
			</tr>
		</table>
	</div>
</div>

<div id="accordion_datasource_excludearticles" class="sqlquerier panel panel-info panel-group adminform">
	<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#datasource_excludearticles"><h4><?php echo JText::_('COM_JMAP_ARTICLES_EXCLUSION' ); ?></h4></div>
	<div class="panel-body panel-collapse collapse" id="datasource_excludearticles">
		<table  class="admintable">
			<tr>
				<td class="paramlist_key left_title">
					<span class="editlinktip"><label id="paramstitle-lbl" for="paramstitle" class="hasPopover" data-content="<?php echo JText::_('COM_JMAP_CHOOSE_ARTICLES_EXCLUSION_DESC');?>"><?php echo JText::_('COM_JMAP_CHOOSE_ARTICLES_EXCLUSION');?></label></span>
				</td>
				<td class="paramlist_value">
					<?php echo $this->lists['articleexclusion']; ?>
				</td>
			</tr>
		</table>
	</div>
</div>

<div id="accordion_datasource_catspriorities" class="sqlquerier panel panel-info panel-group adminform">
	<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#datasource_catspriorities"><h4><?php echo JText::_('COM_JMAP_CATS_PRIORITIES' ); ?></h4></div>
	<div class="panel-body panel-collapse collapse" id="datasource_catspriorities">
		<table  class="admintable">
			<tr>
				<td class="paramlist_key left_title">
					<span class="editlinktip"><label id="paramstitle-lbl" for="paramstitle" class="hasPopover" data-content="<?php echo JText::_('COM_JMAP_ASSIGN_CATS_PRIORITIES_DESC');?>"><?php echo JText::_('COM_JMAP_ASSIGN_CATS_PRIORITIES');?></label></span>
				</td>
				<td class="paramlist_value">
					<?php echo $this->lists['cats_priorities']; ?>
					<?php echo $this->lists['priorities']; ?>
					<div id="controls_grouper">
						<button data-role="priority_action" data-action="store" data-type="CatsPriorities" class="btn btn-xs btn-primary active"><span class="glyphicon glyphicon-floppy-disk"></span><?php echo JText::_('COM_JMAP_ASSIGN_MENU_PRIORITIES_BTN');?></button>
						<button data-role="priority_action" data-action="remove" data-type="CatsPriorities" class="btn btn-xs btn-default active"><span class="glyphicon glyphicon-remove"></span><?php echo JText::_('COM_JMAP_REMOVE_MENU_PRIORITIES_BTN');?></button>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>