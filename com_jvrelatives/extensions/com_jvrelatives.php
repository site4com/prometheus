<?php
/**
 * @version		$Id$
 * @package		JV-Relatives
 * @subpackage	com_jvrelatives
 * @copyright	Copyright 2008-2013 JV-Extensions. All rights reserved
 * @license		GNU General Public License version 3 or later
 * @author		JV-Extensions
 * @link		http://www.jv-extensions.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

interface JvrelativesComponentInterface
{
	public function loadArticleInfo($aid);
	public function check($article);
	public function getMetaKeys($article);
	public function getRelatedArticles($article, $metakeys);
	public function getFallbackRelatedArticles($article);
	public function prepareKeywordTagging($tags, $dummy, $title);
	public function prepareSocialMediaGraphics();
	public function getArticleUrl($relartobj);
	public function isThumbnailEnabled();
	public function getImageThumbnail($relart);
	public function isTagEnabled();
	public function isSocialMediaEnabled();
}

abstract class JvrelativesComponent
{
	public $component;
	public $context;
	public $dviews = array();
	public $module = 0;

	public function __construct($component, $context, $dviews)
	{
		$this->component = $component;
		$this->context = $context;
		$this->dviews = $dviews;
		$this->module = (JString::substr($this->context, 0, 15) == 'mod_jvrelatives') ? 1 : 0;
	}

	public function isModule()
	{
		return $this->module;
	}

	public function displayInThisView()
	{
		$view = JFactory::getApplication()->input->getCmd('view', '');
		return (in_array($view, $this->dviews)) ? 1 : 0;
	}

	public function check($article)
	{
		if (!$this->checkExcludeURLsOption())
			throw new Exception();
	}

	public function checkExcludeURLsOption()
	{
		if (JvrelInit::getCfg('exclude_urls') != '')
		{
			$exurls = explode(",", JvrelInit::getCfg('exclude_urls'));
			for ($i=0;$i<count($exurls);$i++)
			{
				$url_string = preg_quote(JString::trim($exurls[$i]), "/");
				if (preg_match("/".$url_string."/i", $_SERVER['REQUEST_URI']))
				{
					JvrelInit::debug("URL has string that is in exclude urls config");
					return 0;
				}
			}
		}
		return 1;
	}

	public function isDisplaySwitchedOff(&$content)
	{
		if ($content == '')
			return 0;

		if (preg_match("/{jvrelatives off}/i", $content))
		{
			$content = preg_replace("/{jvrelatives off}/i", "", $content);

			JvrelInit::debug("jvrelatives off detected");
			return 1;
		}
		return 0;
	}

	public function verifyTagPositionAvailabilityWithConfig($content)
	{
		if ($content == '')
			return 1;

		if (!$this->isModule())
		{
			if ((JvrelInit::getCfg('disp_position') == 4) && (!preg_match("/{jvrelatives}/i", $content)))
				return 0;
		}

		return 1;
	}

	public function getMetaKeys($metakeystr)
	{
		if (($metakeystr == '') || ($metakeystr == NULL) || (JString::strlen($metakeystr) < 2))
			return array();

		$metakeys_array = $this->cleanKeywords($metakeystr);
		JvrelInit::debug("meta keywords of article: ", $metakeys_array);
		return $metakeys_array;
	}

	public function getMetakeysFromAceSEF()
	{
		JvrelInit::debug("Fetching meta keywords of article from AceSEF");

		$current_sefurl = JString::str_ireplace(JUri::root(), "", JUri::current());

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("keywords")->from("#__acesef_metadata")->where("url_sef = '".$db->escape($current_sefurl)."'");
		$db->setQuery($query);
		return $db->loadResult();
	}

	public function getMetakeysFromsh404SEF()
	{
		JvrelInit::debug("Fetching meta keywords of article from sh404SEF");

		$current_sefurl = JString::str_ireplace(JUri::root(), "", JUri::current());

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("m.metakey")->from("#__sh404sef_metas as m")->join("inner", "#__sh404sef_urls as u on m.newurl = u.newurl")->where("u.oldurl = '".$db->escape($current_sefurl)."'");
		$db->setQuery($query);
		return $db->loadResult();
	}

	// Note: for now this function works only for Joomla article tags though its in parent class
	public function getExtensionTagsFromJoomla($id, $type_alias)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select("t.title");
		$query->from("#__tags as t");
		$query->join("inner", "#__contentitem_tag_map as m on t.id = m.tag_id");
		$query->where("t.published = 1");
		$query->where("m.type_alias = '".$db->escape($type_alias)."'");
		$query->where("m.content_item_id = ".(int)$id)->order("t.title asc");

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$metakeys = '';
		foreach ($rows as $row)
			$metakeys .= $row->title.", ";

		if ($metakeys != '')
			$metakeys = JString::trim($metakeys, ", ");

		return $metakeys;
	}

	public function cleanKeywords($metakeystr)
	{
		$newmetakeys = array();
		$ignored_keywords_array = array();

		if (JvrelInit::getCfg('ignore_keywords') != '')
		{
			$ignored_keywords_array = explode(',',JvrelInit::getCfg('ignore_keywords'));
			$ignored_keywords_array = array_map(array('JString', 'strtolower'), array_map(array('JString', 'trim'), $ignored_keywords_array));
		}

		$keywords = explode(',', $metakeystr);
		for ($i=0;$i<count($keywords);$i++)
		{
			$ignore = 0;
			$kword = JString::strtolower(JString::trim($keywords[$i]));
			for ($j=0;$j<count($ignored_keywords_array);$j++)
			{
				if ($ignored_keywords_array[$j] == $kword)
				{
					$ignore = 1;
					break;
				}
			}

			if (!$ignore)
				array_push($newmetakeys, JString::trim($keywords[$i]));
		}

		return $newmetakeys;
	}

	public function getRelatedArticleIDsFromAceSEF($metakeys, $url_search, $id_param)
	{
		JvrelInit::debug("Fetching related articles from AceSEF");

		$related_ids = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("u.url_real");
		$query->from("#__acesef_urls as u");
		$query->join("inner", "#__acesef_metadata as m on u.url_sef = m.url_sef");
		$query->where("u.url_real like '%index.php?option=".$this->component."%'");
		$query->where("u.url_real like '%".$url_search."%'");

		$sql = "";
		for ($i=0;$i<count($metakeys);$i++)
			$sql .= "m.title like '%".$db->escape($metakeys[$i], true)."%' or m.keywords like '%".$db->escape($metakeys[$i], true)."%' or ";

			if ($sql != "")
					$query->where("(".JString::substr($sql, 0, JString::strlen($sql)-4).")");

			$query->order("RAND() limit 0, 100");

			$db->setQuery($query);
			$rows = $db->loadObjectList();

			if (!count($rows))
				return $related_ids;

			foreach ($rows as $row)
			{
				preg_match('/&'.$id_param.'=\d+/', $row->url_real, $matches);
				if (($matches == null) || (!isset($matches[0])) || ($matches[0] == ''))
					continue;

				$related_ids[] = substr($matches[0], 2+strlen($id_param));
			}

			return $related_ids;
	}

	public function getRelatedArticleIDsFromsh404SEF($metakeys, $url_search, $id_param)
	{
		JvrelInit::debug("Fetching related articles from sh404SEF");

		$related_ids = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("u.newurl");
		$query->from("#__sh404sef_urls as u");
		$query->join("inner", "#__sh404sef_metas as m on u.newurl = m.newurl");
		$query->where("u.newurl like '%index.php?option=".$this->component."%'");
		$query->where("u.newurl like '%".$url_search."%'");

		$sql = "";
		for ($i=0;$i<count($metakeys);$i++)
			$sql .= "m.metatitle like '%".$db->escape($metakeys[$i], true)."%' or m.metakey like '%".$db->escape($metakeys[$i], true)."%' or ";

			if ($sql != "")
					$query->where("(".JString::substr($sql, 0, JString::strlen($sql)-4).")");

			$query->order("RAND() limit 0, 100");

			$db->setQuery($query);
			$rows = $db->loadObjectList();

			if (!count($rows))
				return $related_ids;

			foreach ($rows as $row)
			{
				preg_match('/&'.$id_param.'=\d+/', $row->newurl, $matches);
				if (($matches == null) || (!isset($matches[0])) || ($matches[0] == ''))
					continue;

				$related_ids[] = substr($matches[0], 2+strlen($id_param));
			}

			return $related_ids;
	}

	// Note: for now this function works only for Joomla article tags though its in parent class
	public function getRelatedArticleIDsFromJoomlaTags($tags, $type_alias, $in_article_id)
	{
		JvrelInit::debug("Fetching related articles from Joomla Tags");

		$related_ids = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select("distinct m.content_item_id as id");
		$query->from("#__contentitem_tag_map as m");
		$query->where("m.type_alias = '".$db->escape($type_alias)."'");
		$query->join("inner", "#__tags as t on m.tag_id = t.id");
		$query->where("t.published = 1");
		$query->where("m.content_item_id != ".(int)$in_article_id);

		$sql = "";
		for ($i=0;$i<count($tags);$i++)
			$sql .= "t.title = '".$db->escape($tags[$i])."' or ";

		if ($sql != "")
				$query->where("(".JString::substr($sql, 0, JString::strlen($sql)-4).")");

		$query->order("RAND() limit 0, 100");

		JvrelInit::debug('Relart tags query: '.nl2br($query));

		$db = JFactory::getDbo();
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		if (!count($rows))
			return $related_ids;

		foreach ($rows as $row)
			$related_ids[] = $row->id;

		return $related_ids;
	}

	public function prepareKeywordTagging($tags, $urls, $title)
	{
		$tmpl = new JvrelTemplate('tmpl_tagblock');
		$tmpl->ismodule = $this->isModule();
		$tmpl->target = JvrelInit::getCfg('tag_link_search_wintarget') ? 'target="_blank"' : '';
		$tmpl->title = $title;
		$tmpl->tags = $tags;
		$tmpl->urls = $urls;
		return $tmpl->getContents();
	}

	public function prepareSocialMediaGraphics()
	{
		$tmpl = new JvrelTemplate('tmpl_socialblock');
		return $tmpl->getContents();
	}

	public function getComponentItemID()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("id")->from("#__menu")->where("type = 'component' and link like 'index.php?option=".$db->escape($this->component, true)."%'")->order("id asc limit 0, 1");
		$db->setQuery($query);
		$row = $db->loadObject();

		return ($row) ? $row->id : 0;
	}

	public function getDistance($relartwords, $dispartwords)
	{
		for ($sum=0,$i=0;$i<count($relartwords);$i++)
		{
			for ($ds=0,$j=0;$j<count($dispartwords);$j++)
			{
				$d = levenshtein($relartwords[$i], $dispartwords[$j]);
				if (!$d)
				{
					$ds = 0;
					break;
				}
				else
				{
					$ds += $d;
				}
			}
			$sum += $ds;
		}

		return round($sum/count($relartwords), 2);
	}

	public function sortRelatedArticlesByDistance($obj1, $obj2)
	{
		if ($obj1->distance == $obj2->distance)
			return 0;

		else if ($obj1->distance > $obj2->distance)
			return 1;

		else
			return -1;
	}

	public function getSearchItemId()
	{
		$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select("id")->from("#__menu")->where("type = 'component' and link = 'index.php?option=com_search'")->order("id asc limit 0, 1");
    	$db->setQuery($query);
    	$row = $db->loadObject();

    	return ($row) ? $row->id : 0;
	}

	public function getFinderItemId()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("id")->from("#__menu")->where("type = 'component' and link = 'index.php?option=com_finder'")->order("id asc limit 0, 1");
		$db->setQuery($query);
		$row = $db->loadObject();

		return ($row) ? $row->id : 0;
	}
}
