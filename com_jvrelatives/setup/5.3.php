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

class JvrelVersionInstaller_5_3 extends JvrelVersionInstaller
{
	function __construct($version)
	{
		$sqls = array(
				"alter table #__jvrelatives add `default_thumbnail` varchar(255) not null default 'images/jvrel_thumbnail.gif', add `no_relatives_text_show` tinyint unsigned default '1', add `no_metakeys_text_show` tinyint unsigned default '1'",
				"alter table #__jvrelatives drop `disp_position`, drop `block_bgcolor`, drop `block_fgcolor`, drop `block_width`, drop `mambot_caption`, drop `show_thumbnail`, drop `include_categories_content`, drop `tag_where`",
				"alter table #__jvrelatives drop `metakeys_source_content`, drop `metakeys_source_myblog`, drop `metakeys_source_easyblog`, drop `tag_en_content`, drop `tag_en_myblog`, drop `tag_en_easyblog`, drop `search_title_content`",
				"alter table #__jvrelatives drop `search_title_myblog`, drop `search_title_easyblog`, drop `metakeys_source_k2`, drop `tag_en_k2`, drop `search_title_k2`, drop `include_categories_easyblog`, drop `include_categories_k2`",
				"alter table #__jvrelatives drop `en_content`, drop `en_myblog`, drop `en_easyblog`, drop `en_k2`, drop `social_where`, drop `social_en_content`, drop `social_en_myblog`, drop `social_en_easyblog`, drop `social_en_k2`",
				"alter table #__jvrelatives drop `relart_fallback_content`, drop `relart_fallback_k2`, drop `relart_fallback_easyblog`, drop `search_in_my_category_content`, drop `search_in_my_category_myblog`, drop `search_in_my_category_easyblog`", 
				"alter table #__jvrelatives drop `search_in_my_category_k2`, drop `show_thumbnail_content`, drop `show_thumbnail_k2`, drop `show_thumbnail_easyblog`, drop `module_tags_show`, drop `module_social_show`",
		); 
		
		parent::__construct($version, $sqls);
	}
}