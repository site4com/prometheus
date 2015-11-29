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

class JvrelVersionInstaller_5_2 extends JvrelVersionInstaller
{
	function __construct($version)
	{
		$sqls = array(
					"alter table #__jvrelatives add `tag_link_search_wintarget` tinyint unsigned default '0', add `social_code` longtext not null, add `module_tags_show` tinyint unsigned default '0', add `module_social_show` tinyint unsigned default '0'",
					"update #__jvrelatives set `social_code` = '<!-- AddThis Button BEGIN -->
																<div class=\"addthis_toolbox addthis_default_style addthis_32x32_style\">
																	<a class=\"addthis_button_preferred_1\"></a>
																	<a class=\"addthis_button_preferred_2\"></a>
																	<a class=\"addthis_button_preferred_3\"></a>
																	<a class=\"addthis_button_preferred_4\"></a>
																	<a class=\"addthis_button_compact\"></a>
																	<a class=\"addthis_counter addthis_bubble_style\"></a>
																</div>
																<script type=\"text/javascript\">var addthis_config = {\"data_track_addressbar\":true};</script>
																<script type=\"text/javascript\" src=\"//s7.addthis.com/js/300/addthis_widget.js#pubid=sniranjan\"></script>
																<!-- AddThis Button END -->'",
				);

		parent::__construct($version, $sqls);
	}
}