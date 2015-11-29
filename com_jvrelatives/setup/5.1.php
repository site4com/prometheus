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

class JvrelVersionInstaller_5_1 extends JvrelVersionInstaller
{
	function __construct($version)
	{
		$sqls = array(
					"alter table #__jvrelatives add `thumbnail_align` tinyint unsigned default '0', add `show_thumbnail_content` tinyint unsigned default '0', add `show_thumbnail_k2` tinyint unsigned default '0', add `show_thumbnail_easyblog` tinyint unsigned default '0', add `show_thumbnail_height` smallint unsigned default '0'",
				);

		parent::__construct($version, $sqls);
	}
}