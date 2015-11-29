<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php if (!empty($this->sidebar)) : ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif;?>

	<table class="table table-hover">
	<tbody>
		<tr>
			<td>
				<p class="pagination-centered">
					<input class="btn btn-info" type="button" name="refresh1" id="refresh1" value="<?php echo JText::_("COM_JVRELATIVES_LOG_REFRESH"); ?>" onclick="javascript:document.location.href='index.php?option=com_jvrelatives&view=log';" />
					<input class="btn btn-danger" type="button" name="clear1" id="clear1" value="<?php echo JText::_("COM_JVRELATIVES_LOG_CLEAR"); ?>" onclick="javascript:document.location.href='index.php?option=com_jvrelatives&task=log.clear';" />
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<textarea name="dbg" readonly="readonly" style="width:100%;" rows="40"><?php echo str_ireplace("<br />", "\n", $this->dbginfo); ?></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<p class="pagination-centered">
					<input class="btn btn-info" type="button" name="refresh1" id="refresh1" value="<?php echo JText::_("COM_JVRELATIVES_LOG_REFRESH"); ?>" onclick="javascript:document.location.href='index.php?option=com_jvrelatives&view=log';" />
					<input class="btn btn-danger" type="button" name="clear1" id="clear1" value="<?php echo JText::_("COM_JVRELATIVES_LOG_CLEAR"); ?>" onclick="javascript:document.location.href='index.php?option=com_jvrelatives&task=log.clear';" />
				</p>
			</td>
		</tr>
	</tbody>
	</table>
	
	<?php JvrelativesHelper::showFooter(); ?>
</div>