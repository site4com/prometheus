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

JFormHelper::loadFieldClass('list');

class JFormFieldJvrelCategory extends JFormFieldList
{
	protected $type = 'JvrelCategory';

	protected function getOptions()
	{
		$options = array();
		$extension = $this->element['extension']; // com_easyblog/com_k2

		try {
			switch ($extension)
			{
				case 'com_easyblog': $this->getDataForEasyBlog(0, $options); break;
				case 'com_k2': $this->getDataForK2(0, $options); break;
				default: break;
			}
		}
		catch (Exception $ex) {
			//echo $ex->getMessage();
		}

		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}

	private function getDataForEasyBlog($id, &$options, $level=0)
	{
		$level++;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select("id as value, title as text");
		$query->from("#__easyblog_category");
		$query->where("parent_id = ".$id);
		$query->order("title asc");

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		if (!count($rows))
		{
			$level--;
			return;
		}

		foreach ($rows as $row)
		{
			$row->text = str_repeat("- ", $level) . $row->text;
			$options[] = $row;
			$this->getDataForEasyBlog($row->value, $options, $level);
		}
	}

	private function getDataForK2($id, &$options, $level=0)
	{
		$level++;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select("id as value, name as text");
		$query->from("#__k2_categories");
		$query->where("parent = ".$id);
		$query->order("name asc");

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		if (!count($rows))
		{
			$level--;
			return;
		}

		foreach ($rows as $row)
		{
			$row->text = str_repeat("- ", $level) . $row->text;
			$options[] = $row;
			$this->getDataForK2($row->value, $options, $level);
		}
	}

}
