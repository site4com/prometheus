<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php if (!empty($this->sidebar)) : ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif;?>

	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<table class="table table-striped">
				<tbody>
					<tr class="success">
						<td id="start"><strong>Step-1: Initiation</strong></td>
					</tr>
	<?php for ($i=0;$i<count($this->versions_to_upgrade);$i++) : ?>
					<tr id="<?php echo $this->versions_to_upgrade[$i]; ?>">
						<td><strong>Step-<?php echo $i+2; ?>: Upgrade to <?php echo $this->versions_to_upgrade[$i]; ?></strong></td>
					</tr>
	<?php endfor; ?>
				</tbody>
				</table>
			</div>
			<div class="span9">
	    		<div class="progress">
	    			<div id="pbar" class="bar" style="width: 1%;"></div>
	    		</div>
	    		<div class="well">
	    			<p><?php echo $this->desc; ?></p>
	    			<p class="center"><input class="btn btn-primary" type="button" name="install" value="Install/Upgrade" id="install" /></p>
					<div id="dashboard" style="display:none;">
						<p class="text-center bg-info"><a href="index.php?option=com_jvrelatives&view=jvrelatives" class="btn btn-success" id="gtd">Go to JV-Relatives Dashboard</a></p>
					</div>    			
	    			<div id="iarea"></div>
	    		</div>
			</div>
		</div>
	</div>
</div>