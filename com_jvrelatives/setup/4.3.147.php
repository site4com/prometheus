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

class JvrelVersionInstaller_4_3_147 extends JvrelVersionInstaller 
{
	function __construct($version)
	{
		$sqls = array(
					"alter table #__jvrelatives add `cache` tinyint unsigned default '0'", 
					"alter table #__jvrelatives add `cache_lifetime` smallint unsigned default '30'", 
				);
	
		parent::__construct($version, $sqls);
	}
}