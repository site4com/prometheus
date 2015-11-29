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

class JvrelVersionInstaller_0_0 extends JvrelVersionInstaller
{
	function __construct($version)
	{
		$sqls = array(
					"create table if not exists #__jvrelatives (
						`id` tinyint unsigned not null default '1',
						`version` varchar(40) NOT NULL default '',
						`new_window` tinyint unsigned default '0',
						`sort_criteria` tinyint unsigned default '8',
						`show_intro` tinyint unsigned default '0',
                        `show_title_charcnt` int unsigned default '30',
                        `show_intro_charcnt` int unsigned default '160',
                        `expand_title_show_intro` int unsigned default '0',
						`show_timestamp_info` tinyint unsigned default '2',
						`show_hits` tinyint unsigned default '1',
						`show_creator` tinyint unsigned default '1',
						`no_relatives_text` varchar(255) not null default 'There are no related articles for this article',
						`ignore_keywords` varchar(500) not null default '',
						`only_from_author` int not null default '0',
                        `recent_days` int unsigned default '0',
						`exclude_urls` varchar(2048) not null default '',
						`tag_link_search` tinyint unsigned default '0',
						`poweredby` tinyint unsigned default '0',
						`debug` tinyint unsigned default '0',
						`link_thumbnail` tinyint unsigned default '0',
						`timestamp_format` varchar(40) not null default '%a, %d %b %Y, %H:%M:%S',
						`cache` tinyint unsigned default '0',
                        `cache_lifetime` int unsigned default '30',
						`no_metakeys_text` varchar(255) not null default 'Metakeywords are not configured for this article',
						`tag_link_search_wintarget` tinyint unsigned default '0',
						`social_code` longtext not null,
						`default_thumbnail` varchar(255) not null default 'images/jvrel_thumbnail.gif',
						`no_relatives_text_show` tinyint unsigned default '1',
						`no_metakeys_text_show` tinyint unsigned default '1',
                        `style` varchar(255) not null default 'default',
                        `load_bootstrap_css` tinyint unsigned default '0',
                        `load_bootstrap_js` tinyint unsigned default '0',
						`eopsob_en` tinyint unsigned default '1',
						`eopsob_num_articles` tinyint unsigned default '1',
						`eopsob_show_article_image` tinyint unsigned default '0',
						`eopsob_show_tags` tinyint unsigned default '0',
						`eopsob_show_smedia` tinyint unsigned default '0',
						`eopsob_box_width` tinyint unsigned default '25',
						`eopsob_only` tinyint unsigned default '0',
						primary key(`id`)
				) engine=myisam default charset=utf8",

				"insert into #__jvrelatives (id) values('1')",
			);

		parent::__construct($version, $sqls);
	}
}