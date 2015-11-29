<?php
/**
 * @version		$Id: config.php 112 2011-06-13 18:52:28Z sniranjan $
 * @package		JV-Relatives
 * @subpackage	com_jvrelatives
 * @copyright	Copyright 2008-2013 JV-Extensions. All rights reserved
 * @license		GNU General Public License version 3 or later
 * @author		JV-Extensions
 * @link		http://www.jv-extensions.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');

class JvrelativesModelConfig extends JModelAdmin
{
	public function getTable($type = 'Config', $prefix = 'JvrelativesTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_jvrelatives.config', 'config', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
			return false;

		return $form;
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_jvrelatives.edit.config.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
}