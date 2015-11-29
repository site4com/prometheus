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

jimport('joomla.database.table');

class JvrelativesTableConfig extends JTable
{
	public function __construct($db)
	{
		parent::__construct('#__jvrelatives', 'id', $db);
	}

	public function store($updateNulls = false)
	{
		try
		{
			if ($this->default_thumbnail != '')
			{
				if ((JString::substr($this->default_thumbnail, 0, 7) == 'http://') || (JString::substr($this->default_thumbnail, 0, 8) == 'https://'))
					throw new Exception(JText::_("COM_JVRELATIVES_CONFIG_ERR_1"));	
			}
		}
		catch (Exception $ex)
		{
			$this->setError($ex->getMessage());
			return false;
		}
		
		return parent::store($updateNulls);
	}
}