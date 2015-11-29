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

class JvrelVersionInstaller_4_4 extends JvrelVersionInstaller 
{
	function __construct($version)
	{
		$sqls = array(
					"alter table #__jvrelatives add `metakeys_source_content` tinyint unsigned default '0', add `metakeys_source_myblog` tinyint unsigned default '0', add `metakeys_source_easyblog` tinyint unsigned default '0', add `tag_en_content` tinyint unsigned default '1', add `tag_en_myblog` tinyint unsigned default '1', add `tag_en_easyblog` tinyint unsigned default '1', add `search_title_content` tinyint unsigned default '0', add `search_title_myblog` tinyint unsigned default '0', add `search_title_easyblog` tinyint unsigned default '0', add `metakeys_source_k2` tinyint unsigned default '0', add `tag_en_k2` tinyint unsigned default '1', add `search_title_k2` tinyint unsigned default '0', add `include_categories_content` varchar(500) not null default '', add `include_categories_easyblog` varchar(500) not null default '', add `include_categories_k2` varchar(500) not null default '', add `en_content` tinyint unsigned default '1', add `en_myblog` tinyint unsigned default '1', add `en_easyblog` tinyint unsigned default '1', add `en_k2` tinyint unsigned default '1', add `social_twitter` tinyint unsigned default '1', add `social_facebook` tinyint unsigned default '1', add `social_linkedin` tinyint unsigned default '1', add `social_googleplus` tinyint unsigned default '1', add `social_email` tinyint unsigned default '1', add `social_where` tinyint unsigned default '1', add `social_en_content` tinyint unsigned default '1', add `social_en_myblog` tinyint unsigned default '1', add `social_en_easyblog` tinyint unsigned default '1', add `social_en_k2` tinyint unsigned default '1'",
					"alter table #__jvrelatives drop `tag_listing_bgcolor`, drop `tag_en`, drop `tag_title`, drop `tag_listing_fgcolor`, drop `search_title`, drop `no_metakeys_text`, drop `exclude_categories`, drop `search_criteria`, drop `norel_showcat_articles`",
				);
	
		parent::__construct($version, $sqls);
	}
}