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

require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class JvrelativesCom_content extends JvrelativesComponent implements JvrelativesComponentInterface
{
	public function __construct($context)
	{
		parent::__construct("com_content", $context, array('article'));
	}

	// This function is used to load the information about the article that is displayed on the screen
	public function loadArticleInfo($aid)
	{
		JvrelInit::debug("In com_content loadArticleInfo with aid: ".(int)$aid);

		try
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select("`id` as id, `title` as title, concat(`introtext`, `fulltext`) as text, `catid` as catid, `metakey` as metakey");
			$query->from("#__content");
			$query->where("id = ".(int)$aid);

			$db->setQuery($query);
			return $db->loadObject();
		}
		catch (Exception $ex)
		{
			JvrelInit::debug("Error: ".$ex->getMessage());
			return null;
		}
	}

	public function check($article)
	{
		try {
			parent::check($article);

			if (parent::isDisplaySwitchedOff($article->text))
				throw new Exception("related articles display switched off");

			if (!parent::verifyTagPositionAvailabilityWithConfig($article->text))
				throw new Exception("verifyTagPositionAvailabilityWithConfig failed");

			return true;
		}
		catch (Exception $ex)
		{
			JvrelInit::debug("Component check failed: ".$ex->getMessage());
			return false;
		}
	}

	public function getMetaKeys($article)
	{
		switch (JvrelInit::getCfg('metakeys_source_content'))
		{
			case 1: $metakeystr = $this->getMetakeysFromAceSEF(); break;
			case 2: $metakeystr = $this->getMetakeysFromsh404SEF(); break;
			case 3: $metakeystr = $this->getExtensionTagsFromJoomla($article->id, 'com_content.article'); break;
			default: $metakeystr = $article->metakey; break;
		}

		return parent::getMetaKeys($metakeystr);
	}

	public function getRelatedArticles($article, $metakeys)
	{
		switch (JvrelInit::getCfg('metakeys_source_content'))
		{
			case 1: return $this->getRelatedArticlesFromAceSEF($article, $metakeys); break;
			case 2: return $this->getRelatedArticlesFromsh404SEF($article, $metakeys); break;
			case 3: return $this->getRelatedArticlesFromJoomlaTags($article, $metakeys); break;
			default: return $this->getMyRelatedArticles($article, $metakeys); break;
		}
	}

	public function getFallbackRelatedArticles($article)
	{
		$related_articles = array();

		// No related articles. Should i fallback to articles in the same category?
		if (JvrelInit::getCfg('relart_fallback_content'))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$this->getHeaderSql($query, $article);

			/* Specific checks start */

			$query->where("a.catid = ".$article->catid);

			/* Specific checks end */

			$this->getFooterSql($query, 0);

			JvrelInit::debug('Fallback query: '.nl2br($query));

			$db->setQuery($query);
			$rows = $db->loadObjectList();
			if (count($rows))
			{
				foreach ($rows as $relartobj)
					array_push($related_articles, $relartobj);
			}
		}

		return $related_articles;
	}

	public function isTagEnabled()
	{
		return JvrelInit::getCfg('tag_en_content');
	}

	public function prepareKeywordTagging($metakeys, $dummy=array(), $title='')
	{
		$urls = array();
		for ($i=0;$i<count($metakeys);$i++)
		{
			switch (JvrelInit::getCfg('tag_link_search'))
			{
				case 1: {
					$urls[$i] = JRoute::_('index.php?option=com_search&Itemid='.$this->getSearchItemId().'&searchword='.urlencode($metakeys[$i]).'&ordering=newest&searchphrase=all');
					break;
				}
				case 2: {
					$urls[$i] = JRoute::_('index.php?option=com_finder&Itemid='.$this->getFinderItemId().'&q='.urlencode($metakeys[$i]));
					break;
				}
				default:
				case 0: {
					$urls[$i] = '#';
					break;
				}
			}
		}

		$title = (JvrelInit::getCfg('metakeys_source_content') == 3) ? JText::_('COM_JVRELATIVES_TAGS') : JText::_('COM_JVRELATIVES_KEYWORDS');
		return parent::prepareKeywordTagging($metakeys, $urls, $title);
	}

	public function isSocialMediaEnabled()
	{
		return JvrelInit::getCfg('social_en_content');
	}

	public function prepareSocialMediaGraphics()
	{
		return parent::prepareSocialMediaGraphics();
	}

	public function getArticleUrl($relartobj)
	{
		return JRoute::_(ContentHelperRoute::getArticleRoute($relartobj->item_aslug, $relartobj->item_cslug));
	}

	public function isThumbnailEnabled()
	{
		return JvrelInit::getCfg('show_thumbnail_content');
	}

	public function getImageThumbnail($relart)
	{
		JvrelInit::debug("In com_content getImageThumbnail");

		switch (JvrelInit::getCfg('show_thumbnail_content'))
		{
			case 0: return '';
			case 1: {
				if (preg_match_all("/<img(.*?)>/is", $relart->item_text, $matches))
				{
					$imgtag = '<img '.$matches[1][0].'>';
					$imgval = JvrelInit::getTagParam("src", $imgtag);
					return ((substr($imgval, 0, 7) == "http://") || (substr($imgval, 0, 8) == "https://")) ? $imgval : JUri::root().$imgval;
				}

				return '';
			}
			case 2:
			case 3: {
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);

				$query->select("images")->from("#__content")->where("id = ".$relart->item_id);
				$db->setQuery($query);
				$images = json_decode($db->loadResult(), true);

				if (count($images) == 0)
					return '';

				if (JvrelInit::getCfg('show_thumbnail_content') == 2)
				{
					if ((!isset($images['image_intro'])) || ($images['image_intro'] == ''))
						return '';

					$imgval = $images['image_intro'];
				}
				else
				{
					if ((!isset($images['image_fulltext'])) || ($images['image_fulltext'] == ''))
						return '';

					$imgval = $images['image_fulltext'];
				}

				return ((substr($imgval, 0, 7) == "http://") || (substr($imgval, 0, 8) == "https://")) ? $imgval : JUri::root().$imgval;
			}
		}
	}

	private function getMyRelatedArticles($article, $metakeys)
	{
		JvrelInit::debug("Article ID: ".$article->id."<>Metakeys: ".implode(",", $metakeys));

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$this->getHeaderSql($query, $article);

		/* Specific checks start */

		$query->where("a.metakey != ''");

		if (JvrelInit::getCfg('search_in_my_category_content'))
		{
			$query->where("a.catid = ".$article->catid);
		}
		else
		{
			if (JvrelInit::getCfg('include_categories_content') != '')
				$query->where("a.catid in (".JvrelInit::getCfg('include_categories_content').")");
		}

		$sql = "";
		for ($i=0;$i<count($metakeys);$i++)
		{
			if (JvrelInit::getCfg('search_title_content'))
				$sql .= "a.title like '%".$db->escape($metakeys[$i], true)."%' or ";

			$sql .= "a.metakey like '%".$db->escape($metakeys[$i], true)."%' or ";

			$temp = explode(" ", $metakeys[$i]);
			for ($k=0;$k<count($temp);$k++)
			{
				if (JvrelInit::getCfg('search_title_content'))
					$sql .= "a.title like '%".$db->escape($temp[$k], true)."%' or ";

				$sql .= "a.metakey like '%".$db->escape($temp[$k], true)."%' or ";
			}
		}

		if ($sql != "")
			$query->where("(".JString::substr($sql, 0, JString::strlen($sql)-4).")");

		/* Specific checks end */

		$this->getFooterSql($query, 1);

		JvrelInit::debug('Relart query: '.nl2br($query));

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$related_articles = array();
		if (count($rows))
		{
			if (JvrelInit::getCfg('sort_criteria') == 9)
			{
				foreach ($rows as $relartobj)
					$relartobj->distance = parent::getDistance(explode(",", JString::trim($relartobj->item_keywords)), $metakeys);

				JvrelInit::debug('Before usort: ');
				foreach ($rows as $relartobj)
					JvrelInit::debug('Distance for article: ['.$relartobj->item_title.'] Keys: ['.$relartobj->item_keywords.']: '.$relartobj->distance);

				usort($rows, array($this, 'sortRelatedArticlesByDistance'));

				JvrelInit::debug('After usort: ');
				foreach ($rows as $relartobj)
					JvrelInit::debug('Distance for article: ['.$relartobj->item_title.'] Keys: ['.$relartobj->item_keywords.']: '.$relartobj->distance);

				$i=0;
				foreach ($rows as $relartobj)
				{
					array_push($related_articles, $relartobj);

					$i++;
					if ($i >= JvrelInit::getCfg("num_articles_content"))
						break;
				}
			}
			else
			{
				foreach ($rows as $relartobj)
					array_push($related_articles, $relartobj);
			}
		}

		return $related_articles;
	}

	private function getRelatedArticlesFromAceSEF($article, $metakeys)
	{
		JvrelInit::debug("Article ID: ".$article->id);

		$related_ids = $this->getRelatedArticleIDsFromAceSEF($metakeys, "view=article", "id");
		if (!count($related_ids))
			return array();

		return $this->getMyArticlesFromIDs($article, $related_ids);
	}

	private function getRelatedArticlesFromsh404SEF($article, $metakeys)
	{
		JvrelInit::debug("Article ID: ".$article->id);

		$related_ids = $this->getRelatedArticleIDsFromsh404SEF($metakeys);
		if (!count($related_ids))
			return array();

		return $this->getMyArticlesFromIDs($article, $related_ids);
	}

	private function getRelatedArticlesFromJoomlaTags($article, $metakeys)
	{
		JvrelInit::debug("Article ID: ".$article->id);

		$related_ids = $this->getRelatedArticleIDsFromJoomlaTags($metakeys, 'com_content.article', $article->id);
		if (!count($related_ids))
			return array();

		return $this->getMyArticlesFromIDs($article, $related_ids);
	}

	private function getMyArticlesFromIDs($article, $related_ids)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$this->getHeaderSql($query, $article);

		/* Specific checks start */

		$query->where("a.id in (".implode(",", $related_ids).")");

		if (JvrelInit::getCfg('search_in_my_category_content'))
		{
			$query->where("a.catid = ".$article->catid);
		}
		else
		{
			if (JvrelInit::getCfg('include_categories_content') != '')
				$query->where("a.catid in (".JvrelInit::getCfg('include_categories_content').")");
		}

		/* Specific checks end */

		$this->getFooterSql($query, 0);

		JvrelInit::debug('Relart myids query: '.nl2br($query));

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$related_articles = array();
		if (count($rows))
		{
			foreach ($rows as $relartobj)
				array_push($related_articles, $relartobj);
		}

		return $related_articles;
	}

	private function getHeaderSql($query, $article)
	{
		$datenow = JFactory::getDbo()->Quote(JFactory::getDate()->toSql());
		$nulldate = JFactory::getDbo()->Quote(JFactory::getDbo()->getNullDate());
		$groups = implode(',', JFactory::getUser()->getAuthorisedViewLevels());

		$query->select("distinct a.id as item_id, a.title as item_title, concat(a.introtext, a.fulltext) as item_text, a.created as item_createdon, a.created_by as item_createdby, a.modified as item_modifiedon, a.hits as item_hits, a.metakey as item_keywords");
		$query->select('case when char_length(a.alias) then concat_ws(":", a.id, a.alias) else a.id end as item_aslug');
		$query->select('case when char_length(c.alias) then concat_ws(":", c.id, c.alias) else c.id end as item_cslug');
		$query->from("#__content as a");
		$query->join("inner", "#__categories as c on a.catid = c.id");
		$query->where('a.id != '.(int)$article->id);
		$query->where("a.state = 1");
		$query->where("c.published = 1");
		$query->where("(a.publish_down = ".$nulldate." or a.publish_down >= ".$datenow.")");
		$query->where("a.access in (".$groups.")");

		if (JvrelInit::getCfg('recent_days'))
		{
			$ndate = JFactory::getDate(JFactory::getDate()->toUnix() - (JvrelInit::getCfg('recent_days')*24*3600));
			$dateold = JFactory::getDbo()->Quote($ndate->toSql());
			$query->where("(a.publish_up <= ".$datenow." and a.publish_up >= ".$dateold.")");
		}
		else
			$query->where("(a.publish_up = ".$nulldate." or a.publish_up <= ".$datenow.")");

		$app = JFactory::getApplication();
		if ($app->isSite() && $app->getLanguageFilter())
		{
			$langval = JFactory::getDbo()->Quote(JFactory::getLanguage()->getTag()).",".JFactory::getDbo()->Quote('*');
			$query->where("a.language in (".$langval.")");
		}

		if (JvrelInit::getCfg('only_from_author'))
			$query->where("a.created_by = ".(int)JvrelInit::getCfg('only_from_author'));

		$query->group('item_id');
		return;
	}

	private function getFooterSql($query, $override)
	{
		$sql = "";
		switch (JvrelInit::getCfg('sort_criteria'))
		{
			case 0: $sql = "a.hits asc"; break;
			case 1: $sql = "a.created asc"; break;
			case 2: $sql = "a.modified asc"; break;
			case 3: $sql = "a.title asc"; break;
			case 4: $sql = "a.hits desc"; break;
			case 5: $sql = "a.created desc"; break;
			case 6: $sql = "a.modified desc"; break;
			case 7: $sql = "a.title desc"; break;
			case 9:
				{
					if (!$override)
					{
						$sql = "RAND()";
						break;
					}
					else
						return;
				}
			default: $sql = "RAND()"; break;
		}
		$sql .= " limit 0, ".JvrelInit::getCfg("num_articles_content");
		$query->order($sql);

		return;
	}
}