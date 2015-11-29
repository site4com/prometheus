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

class JvrelVersionInstaller_4_4_1 extends JvrelVersionInstaller 
{
	function __construct($version)
	{
		$sqls = array(
					"alter table #__jvrelatives drop `social_twitter`, drop `social_facebook`, drop `social_linkedin`, drop `social_googleplus`, drop `social_email`",
					"alter table #__jvrelatives add `relart_fallback_content` tinyint unsigned default '1', add `relart_fallback_k2` tinyint unsigned default '1', add `relart_fallback_easyblog` tinyint unsigned default '1', add `no_metakeys_text` varchar(255) not null default 'Metakeywords are not configured for this article'",
				);
	
		parent::__construct($version, $sqls);
	}
}