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

class JFormFieldJvrelStyle extends JFormFieldList
{
	protected $type = 'JvrelStyle';

	protected function getOptions()
	{
		$options = array();

		$template_names = array();
		$t_names = JFolder::listFolderTree(JPATH_ROOT.DS."media".DS."com_jvrelatives".DS."styles", ".", 1);
		foreach ($t_names as $t)
		{
			$row = new StdClass();
			$row->text = $t['name'];
			$row->value = $t['name'];

			$options[] = $row;
		}

		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}
