<?php
/**
 * @version		$Id: controller.php 112 2011-06-13 18:52:28Z sniranjan $
 * @package		JV-LinkExchanger
 * @subpackage	com_jvle
 * @copyright	Copyright 2008-2014 JV-Extensions. All rights reserved
 * @license		GNU General Public License version 3 or later
 * @author		JV-Extensions
 * @link		http://www.jv-extensions.com
 */

defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('list');

class JFormFieldJvrelMenu extends JFormFieldList
{
	protected $type = 'JvrelMenu';

	protected function getOptions()
	{
		$options = array();
		$extension = $this->element['extension']; // com_easyblog/com_k2

		$db = JFactory::getDbo();
		$db->setQuery("select id as value, title as text from #__menu where link like 'index.php?option=".$db->escape($extension)."%' and client_id = 0 and published = 1");
		$rows = $db->loadObjectList();

		if (count($rows))
		{
			foreach ($rows as $row)
				$options[] = $row;
		}

		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}
