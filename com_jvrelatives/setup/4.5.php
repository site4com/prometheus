<?php 
/**
 * @version		$Id$
 * @package		JV-Relatives
 * @subpackage	com_jvrelatives
 * @copyright	Copyright 2008-2012 JV-Extensions. All rights reserved
 * @license		GNU General Public License version 3 or later
 * @author		JV-Extensions
 * @link		http://www.jv-extensions.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

class JvrelVersionInstaller_4_5 extends JvrelVersionInstaller 
{
	function __construct($version)
	{
		$sqls = array(
					"alter table #__jvrelatives add `search_in_my_category_content` tinyint unsigned default '0', add `search_in_my_category_myblog` tinyint unsigned default '0', add `search_in_my_category_easyblog` tinyint unsigned default '0', add `search_in_my_category_k2` tinyint unsigned default '0'",				
				);
	
		parent::__construct($version, $sqls);
	}
}