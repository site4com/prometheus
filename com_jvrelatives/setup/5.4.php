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

class JvrelVersionInstaller_5_4 extends JvrelVersionInstaller
{
	function __construct($version)
	{
		$sqls = array(
				"alter table #__jvrelatives drop num_articles",
				"alter table #__jvrelatives drop num_columns",
				"alter table #__jvrelatives drop show_thumbnail_width",
				"alter table #__jvrelatives drop show_thumbnail_height",
				"alter table #__jvrelatives drop thumbnail_align",
    		    "alter table #__jvrelatives change `show_title_charcnt` `show_title_charcnt` int unsigned default '30'",
    		    "alter table #__jvrelatives change `show_intro_charcnt` `show_intro_charcnt` int unsigned default '160'",
    		    "alter table #__jvrelatives change `expand_title_show_intro` `expand_title_show_intro` int unsigned default '0'",
    		    "alter table #__jvrelatives change `recent_days` `recent_days` int unsigned default '0'",
    		    "alter table #__jvrelatives change `cache_lifetime` `cache_lifetime` int unsigned default '30'",
                "alter table #__jvrelatives add `style` varchar(255) not null default 'default'",
                "alter table #__jvrelatives add `load_bootstrap_css` tinyint unsigned default '0'",
                "alter table #__jvrelatives add `load_bootstrap_js` tinyint unsigned default '0'",
				"alter table #__jvrelatives add `eopsob_en` tinyint unsigned default '1'",
				"alter table #__jvrelatives add `eopsob_num_articles` tinyint unsigned default '1'",
				"alter table #__jvrelatives add `eopsob_show_article_image` tinyint unsigned default '0'",
				"alter table #__jvrelatives add `eopsob_show_tags` tinyint unsigned default '0'",
				"alter table #__jvrelatives add `eopsob_show_smedia` tinyint unsigned default '0'",
				"alter table #__jvrelatives add `eopsob_box_width` tinyint unsigned default '25'",
				"alter table #__jvrelatives add `eopsob_only` tinyint unsigned default '0'",
		);

		parent::__construct($version, $sqls);
	}
}