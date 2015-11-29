<?php
/**
 * @version		$Id: script.php 126 2011-06-18 12:59:39Z sniranjan $
 * @package		JV-Relatives
 * @subpackage	com_jvrelatives
 * @copyright	Copyright 2008-2013 JV-Extensions. All rights reserved
 * @license		GNU General Public License version 3 or later
 * @author		JV-Extensions
 * @link		http://www.jv-extensions.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

if (!defined('DS'))
	define('DS', DIRECTORY_SEPARATOR);

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class com_JvrelativesInstallerScript
{
	private $db;
	private $i_status;

	public function __construct(JAdapterInstance $adapter)
	{
		$this->db = JFactory::getDBO();

		$this->i_status = new stdClass;
		$this->i_status->modules = array();
		$this->i_status->plugins = array();
		$this->i_status->files = array();
	}
	
	private function installPlugin($name, $group, $src)
	{
		$path = $src.DS.'plugins'.DS.$group;
		if (JFolder::exists($src.DS.'plugins'.DS.$group.DS.$name))
			$path = $src.DS.'plugins'.DS.$group.DS.$name;
	
		$installer = new JInstaller;
		return $installer->install($path);
	}
	
	private function uninstallPlugin($name, $group)
	{
		$this->db->setQuery("select extension_id from #__extensions where type = 'plugin' and element = ".$this->db->Quote($name)." and folder = ".$this->db->Quote($group));
		$obj = $this->db->loadObject();
		if (!$obj)
			return 0;
	
		$installer = new JInstaller;
		return $installer->uninstall('plugin', $obj->extension_id);
	}
	
	private function installModule($name, $client, $src)
	{
		$path = ($client == 'administrator') ? $src.DS.'administrator'.DS.'modules'.DS.$name : $src.DS.'modules'.DS.$name;
	
		$installer = new JInstaller;
		return $installer->install($path);
	}
	
	private function uninstallModule($name)
	{
		$this->db->setQuery("select extension_id from #__extensions where type = 'module' and element = ".$this->db->Quote($name));
		$obj = $this->db->loadObject();
		if (!$obj)
			return 0;
	
		$installer = new JInstaller;
		return $installer->uninstall('module', $obj->extension_id);
	}	

	public function preflight($route, JAdapterInstance $adapter)
	{
		if (($route == 'install') || ($route == 'discover_install') || ($route == 'update'))
		{
			// Install the modules and plugins.
			$src = $adapter->getParent()->getPath('source');
			$manifest = $adapter->getParent()->manifest;

			$plugins = $manifest->xpath('plugins/plugin');
			if (count($plugins))
			{
				foreach ($plugins as $plugin)
				{
					$name = (string)$plugin->attributes()->plugin;
					$group = (string)$plugin->attributes()->group;
					$this->i_status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $this->installPlugin($name, $group, $src));
				}
			}
			
			$modules = $manifest->xpath('modules/module');
			if (count($modules))
			{
				foreach ($modules as $module)
				{
					$name = (string)$module->attributes()->module;
					$client = (string)$module->attributes()->client;
					$this->i_status->modules[] = array('name' => $name, 'client' => $client, 'result' => $this->installModule($name, $client, $src));
				}
			}
			
			if (JFolder::exists(JPATH_ROOT.DS.'components'.DS.'com_cobalt')) // Cobalt is installed in Joomla
			{
				if (JFolder::exists($src.DS.'media'.DS.'com_jvrelatives'.DS.'temp'.DS.'com_cobalt'.DS.'fields'.DS.'jvrelatives')) // Cobalt field is available in uploaded package
				{
					try
					{
						// delete jvrelatives field in cobalt if it exists already
						if (JFolder::exists(JPATH_ROOT.DS.'components'.DS.'com_cobalt'.DS.'fields'.DS.'jvrelatives'))
							JFolder::delete(JPATH_ROOT.DS.'components'.DS.'com_cobalt'.DS.'fields'.DS.'jvrelatives');
						
						// Move the cobalt field from media to cobalt fields folder
						$ret = JFolder::move($src.DS.'media'.DS.'com_jvrelatives'.DS.'temp'.DS.'com_cobalt'.DS.'fields'.DS.'jvrelatives', JPATH_ROOT.DS.'components'.DS.'com_cobalt'.DS.'fields'.DS.'jvrelatives');
						if ($ret)
							$this->i_status->files[0] = array('name' => 'cobalt field', 'result' => 1);
						else
							throw new Exception($ret);						
					}	
					catch (Exception $ex)
					{
						$this->i_status->files[0] = array('name' => 'cobalt field', 'result' => 0);
					}				
				}
			}

			if (!JFile::exists(JPATH_ROOT.DS.'images'.DS.'jvrel_thumbnail.gif'))
				JFile::copy($src.DS.'media'.DS.'com_jvrelatives'.DS.'assets'.DS.'images'.DS.'thumbnail.gif', JPATH_ROOT.DS.'images'.DS.'jvrel_thumbnail.gif');				
		}

		return true;
	}

	public function install(JAdapterInstance $adapter)
	{
		$this->showPreflightMessages();
		return true;
	}

	public function update(JAdapterInstance $adapter)
	{
		// Uninstall older type of jv-relatives module and plugin if installed
		$this->uninstallPlugin('jvrelatives', 'content');
		$this->uninstallModule('mod_jvrelatives');
				
		$this->showPreflightMessages();
		return true;
	}

	private function showPreflightMessages()
	{
		$language = JFactory::getLanguage();
		$language->load('com_jvrelatives');

		$rows = 0;
?>
		<div class="alert alert-error">
			<h2 style="text-align:center;"><?php echo JText::_('COM_JVRELATIVES_IMPMSG'); ?></h2>
		</div>

		<h3><?php echo JText::_('COM_JVRELATIVES_INSTALLATION_STATUS'); ?></h3>
        <table class="adminlist table table-striped">
		<thead>
			<tr>
		     	<th class="title" colspan="2"><?php echo JText::_('COM_JVRELATIVES_EXTENSION'); ?></th>
		        <th width="30%"><?php echo JText::_('COM_JVRELATIVES_STATUS'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
		    	<td colspan="3"></td>
			</tr>
		</tfoot>
		<tbody>
			<tr class="row0">
		    	<td class="key" colspan="2"><?php echo 'JV-Relatives '.JText::_('COM_JVRELATIVES_COMPONENT'); ?></td>
		        <td><strong><?php echo JText::_('COM_JVRELATIVES_COMPONENT_INSTALLED'); ?></strong></td>
			</tr>
			<?php if (count($this->i_status->modules)): ?>
            <tr>
            	<th><?php echo JText::_('COM_JVRELATIVES_MODULE'); ?></th>
                <th><?php echo JText::_('COM_JVRELATIVES_CLIENT'); ?></th>
                <th></th>
            </tr>
			<?php 	foreach ($this->i_status->modules as $module): ?>
            <tr class="row<?php echo(++$rows % 2); ?>">
            	<td class="key"><?php echo $module['name']; ?></td>
				<td class="key"><?php echo ucfirst($module['client']); ?></td>
            	<td><strong><?php echo ($module['result']) ? JText::_('COM_JVRELATIVES_INSTALLED') : JText::_('COM_JVRELATIVES_NOT_INSTALLED'); ?></strong></td>
            </tr>
			<?php 	endforeach; ?>
            <?php endif; ?>
            
		    <?php if (count($this->i_status->plugins)): ?>
			<tr>
            	<th><?php echo JText::_('COM_JVRELATIVES_PLUGIN'); ?></th>
                <th><?php echo JText::_('COM_JVRELATIVES_GROUP'); ?></th>
                <th></th>
			</tr>
			<?php 	foreach ($this->i_status->plugins as $plugin): ?>
            <tr class="row<?php echo(++$rows % 2); ?>">
	            <td class="key"><?php echo ucfirst($plugin['name']); ?></td>
	            <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
	            <td><strong><?php echo ($plugin['result']) ? JText::_('COM_JVRELATIVES_INSTALLED'):JText::_('COM_JVRELATIVES_NOT_INSTALLED'); ?></strong></td>
            </tr>
        	<?php 	endforeach; ?>
            <?php endif; ?>
            
		    <?php if (count($this->i_status->files)): ?>
			<tr>
            	<th><?php echo JText::_('COM_JVRELATIVES_FILE'); ?></th>
                <th></th>
                <th></th>
			</tr>
			<?php 	foreach ($this->i_status->files as $file): ?>
            <tr class="row<?php echo(++$rows % 2); ?>">
	            <td class="key"><?php echo ucfirst($file['name']); ?></td>
	            <td class="key"></td>
	            <td><strong><?php echo ($file['result']) ? JText::_('COM_JVRELATIVES_INSTALLED'):JText::_('COM_JVRELATIVES_NOT_INSTALLED'); ?></strong></td>
            </tr>
        	<?php 	endforeach; ?>
            <?php endif; ?>            
            </tbody>
		</table>

    	<br />
   		<div class="alert alert-error">
			<h4><?php echo JText::_("COM_JVRELATIVES_SCRIPT_CONTINUE"); ?>....</h4><br />
   			<p class="text-left"><?php echo JText::_("COM_JVRELATIVES_SCRIPT_STARTED"); ?></p>
   			<p class="text-left"><?php echo JText::_("COM_JVRELATIVES_SCRIPT_PROCEED"); ?></p>
   			<p class="text-center">
   				<a class="btn btn-primary btn-large" href="index.php?option=com_jvrelatives&view=install"><?php echo JText::_("COM_JVRELATIVES_SCRIPT_INSTALL_OR_UPGRADE"); ?></a>
   			</p>
   		</div>
<?php
	}

	public function uninstall(JAdapterInstance $adapter)
	{
		$language = JFactory::getLanguage();
		$language->load('com_jvrelatives');

		$this->db->setQuery("drop table if exists #__jvrelatives");
		$this->db->execute();

		$manifest = $adapter->getParent()->manifest;
		$plugins = $manifest->xpath('plugins/plugin');
		if (count($plugins))
		{
			foreach ($plugins as $plugin)
			{
				$name = (string)$plugin->attributes()->plugin;
				$group = (string)$plugin->attributes()->group;
				$this->i_status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $this->uninstallPlugin($name, $group));
			}
		}
		
		$modules = $manifest->xpath('modules/module');
		if (count($modules))
		{
			foreach ($modules as $module)
			{
				$name = (string)$module->attributes()->module;
				$client = (string)$module->attributes()->client;				
				$this->i_status->modules[] = array('name' => $name, 'client' => $client, 'result' => $this->uninstallModule($name));
			}
		}
		
		if (JFolder::exists(JPATH_ROOT.DS.'components'.DS.'com_cobalt')) // Cobalt is installed in Joomla
		{
			try
			{
				if (JFolder::exists(JPATH_ROOT.DS.'components'.DS.'com_cobalt'.DS.'fields'.DS.'jvrelatives'))
				{
					JFolder::delete(JPATH_ROOT.DS.'components'.DS.'com_cobalt'.DS.'fields'.DS.'jvrelatives');					
					$this->i_status->files[0] = array('name' => 'cobalt field', 'result' => 1);
				}				
			}
			catch (Exception $ex)
			{
				$this->i_status->files[0] = array('name' => 'cobalt field', 'result' => 0);
			}
		}
						
		$rows = 0;
?>
        <h2><?php echo JText::_('COM_JVRELATIVES_REMOVAL_STATUS'); ?></h2>
        <table class="adminlist table table-striped">
            <thead>
                <tr>
                    <th class="title" colspan="2"><?php echo JText::_('COM_JVRELATIVES_EXTENSION'); ?></th>
                    <th width="30%"><?php echo JText::_('COM_JVRELATIVES_STATUS'); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
            <tbody>
                <tr class="row0">
                    <td class="key" colspan="2"><?php echo 'JV-Relatives '.JText::_('COM_JVRELATIVES_COMPONENT'); ?></td>
                    <td><strong><?php echo JText::_('COM_JVRELATIVES_UNINSTALLED'); ?></strong></td>
                </tr>
                <?php if (count($this->i_status->modules)): ?>
                <tr>
                    <th><?php echo JText::_('COM_JVRELATIVES_MODULE'); ?></th>
                    <th><?php echo JText::_('COM_JVRELATIVES_CLIENT'); ?></th>
                    <th></th>
                </tr>
                <?php 	foreach ($this->i_status->modules as $module): ?>
                <tr class="row<?php echo(++$rows % 2); ?>">
                    <td class="key"><?php echo $module['name']; ?></td>
                    <td class="key"><?php echo ucfirst($module['client']); ?></td>
                    <td><strong><?php echo ($module['result']) ? JText::_('COM_JVRELATIVES_UNINSTALLED') : JText::_('COM_JVRELATIVES_NOT_UNINSTALLED'); ?></strong></td>
                </tr>
                <?php 	endforeach; ?>
                <?php endif; ?>

                <?php if (count($this->i_status->plugins)): ?>
                <tr>
                    <th><?php echo JText::_('COM_JVRELATIVES_PLUGIN'); ?></th>
                    <th><?php echo JText::_('COM_JVRELATIVES_GROUP'); ?></th>
                    <th></th>
                </tr>
                <?php 	foreach ($this->i_status->plugins as $plugin): ?>
                <tr class="row<?php echo(++$rows % 2); ?>">
                    <td class="key"><?php echo ucfirst($plugin['name']); ?></td>
                    <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
                    <td><strong><?php echo ($plugin['result']) ? JText::_('COM_JVRELATIVES_UNINSTALLED') : JText::_('COM_JVRELATIVES_NOT_UNINSTALLED'); ?></strong></td>
                </tr>
                <?php 	endforeach; ?>
                <?php endif; ?>
                
				<?php if (count($this->i_status->files)): ?>
                <tr>
                    <th><?php echo JText::_('COM_JVRELATIVES_FILE'); ?></th>
                    <th></th>
                    <th></th>
                </tr>
                <?php 	foreach ($this->i_status->files as $file): ?>
                <tr class="row<?php echo(++$rows % 2); ?>">
                    <td class="key"><?php echo ucfirst($file['name']); ?></td>
                    <td class="key"></td>
                    <td><strong><?php echo ($file['result']) ? JText::_('COM_JVRELATIVES_UNINSTALLED') : JText::_('COM_JVRELATIVES_NOT_UNINSTALLED'); ?></strong></td>
                </tr>
                <?php 	endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <br /><br />
        <div class="well">
        	<?php echo JText::_('COM_JVRELATIVES_UNINSTALL_TEXT_THANKYOU'); ?>
        </div>
<?php
	}
}