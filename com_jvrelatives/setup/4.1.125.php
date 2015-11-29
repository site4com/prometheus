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

class JvrelVersionInstaller_4_1_125 extends JvrelVersionInstaller 
{
	function __construct($version)
	{
		$sqls = array(
					"alter table #__jvrelatives add `version` varchar(40) not null default '' after `id`",
					"alter table #__jvrelatives add `link_thumbnail` tinyint unsigned default '0'",
					"alter table #__jvrelatives add `timestamp_format` varchar(40) not null default '%a, %d %b %Y, %H:%M:%S'",
					"alter table #__jvrelatives add `num_columns` tinyint unsigned default '1'",
				);
	
		parent::__construct($version, $sqls);
	}
}