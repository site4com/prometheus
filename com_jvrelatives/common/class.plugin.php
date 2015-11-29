<?php
/**
 * @version		$Id: plugin.php 134 2011-07-23 04:19:07Z sniranjan $
 * @package		JV-Relatives
 * @subpackage	com_jvrelatives
 * @copyright	Copyright 2008-2013 JV-Extensions. All rights reserved
 * @license		GNU General Public License version 3 or later
 * @author		JV-Extensions
 * @link		http://www.jv-extensions.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jvrelatives'.DS.'common'.DS.'class.cache.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jvrelatives'.DS.'extensions'.DS.'com_jvrelatives.php');

class JvrelativesProcessor
{
	public $show_nometakeys_text = 0;
	public $show_norelarticles_text = 0;

    public $profiler = null;
    public $in_component = '';
    public $context = '';
    public $ismodule = 0;
    public $extn_in_action = '';
    public $eopsob = null;

    public function __construct($context)
    {
    	$this->context = $context;

        if (JvrelInit::getCfg('debug'))
            $this->profiler = JProfiler::getInstance('Application');

        $this->eopsob = new JvrelativesEopsob();
    }

    public function setExtensionInAction($extension_in_action)
    {
    	$this->extn_in_action = $extension_in_action;
    }

    public function action($article, $article_id)
    {
	  	try
    	{
    		// validate context
	        if (!$this->isValidContext())
	            return '';

	        // prevent plugins/modules of one component to run on other component's pages
	        if ($this->isComponentDisplayConflict())
	        {
	        	JvrelInit::debug("Component display conflict detected. Returning. extn in action: ".$this->extn_in_action."<> in_component: ".$this->in_component);
	        	return '';
	        }

	        // create component object
	        $file = JPATH_ADMINISTRATOR.DS."components".DS."com_jvrelatives".DS."extensions".DS.$this->in_component.".php";
	        if (!file_exists($file))
	        {
	        	JvrelInit::debug("Component class file does not exist");
	        	return '';
	        }

	        require_once($file);
	        $clsnm = "Jvrelatives".JString::ucfirst($this->in_component);
	        $obj = new $clsnm($this->context);
	        JvrelInit::debug("Component object created");

	        // check if this view allows related articles to be displayed
	        if (!$obj->displayInThisView())
	        {
	        	JvrelInit::debug("This is a view in which related articles cannot be displayed");
	        	return '';
	        }
        	JvrelInit::debug("View validation ok");

	        // load article info when it is a module
	        if ($this->ismodule)
	        {
	        	$article = $obj->loadArticleInfo($article_id);
	        	if ($article == null)
	        	{
	        		JvrelInit::debug("Article object is null");
	        		return '';
	        	}
	        	JvrelInit::debug("Article info loaded for module: ", $article);
	        }

			// let us do some jv-relatives validations
	        if ($obj->check($article) == false)
	        {
	        	JvrelInit::debug("check failed for the item article");
	        	return '';
	        }
	        JvrelInit::debug("Component check validations passed");

	        // start profiling if debug is turned on
	        if ($this->profiler != null)
	        	JvrelInit::debug($this->profiler->mark('START'));

			$relblock = $tagblock = $socialblock = '';
			if (JvrelInit::getCfg('cache'))
			{
				$cache = new JvrelativesCache($this->in_component, $article->id, JvrelInit::getCfg('cache_lifetime'));
				if ((!$cache->isAvailable()) || ($cache->isExpired()))
				{
					JvrelInit::debug("Cache is not available already or is expired. Reloading Cache...");
					$this->coreProcessor($obj, $article, $relblock, $tagblock, $socialblock);
					$cache->store($relblock, $tagblock);
				}
				else
				{
					JvrelInit::debug("Fetching data from cache");
					$cache->load($relblock, $tagblock);
				}
			}
			else
			{
				JvrelInit::debug("Caching is NOT enabled. Fetching data");
				$this->coreProcessor($obj, $article, $relblock, $tagblock, $socialblock);
			}

			// stop profiling if debug is turned on
			if ($this->profiler != null)
				JvrelInit::debug($this->profiler->mark('STOP'));

			// draw end of page slider
			$this->eopsob->setEndofPageSliderText($tagblock, $socialblock);

			// time to show output
			JvrelInit::debug("To show jv-relatives output");
			return $this->showJvrelativesOutput($obj, $article->text, $relblock, $tagblock, $socialblock);
    	}
    	catch (Exception $ex)
    	{
    		JvrelInit::debug("ERROR: ".$ex->getMessage());
    		return '';
    	}
    }

    public function isValidContext()
    {
    	JvrelInit::debug("<br />*************** New Run Started **********");

    	if (JString::substr($this->context, 0, 15) == 'mod_jvrelatives')
    	{
    		$this->ismodule = 1;
    		$context_option = JString::trim(JString::substr($this->context, 16));
    		$option = JFactory::getApplication()->input->getString('option', '');

    		if ($option != $context_option)
    		{
    			JvrelInit::debug("Entry Point: Module Invokation, but option on url does not match with module context. Url option: ".$option."<> Context option: ".$context_option);
    			return 0;
    		}

    		JvrelInit::debug("Entry Point: Module Invokation on ".$context_option);
    		$this->in_component = $context_option;

    		return 1;
    	}
    	else
    	{
    		$this->ismodule = 0;
    		switch ($this->context)
    		{
    			case 'com_content.article':
    				{
    					JvrelInit::debug("Entry Point: Plugin Invokation on Joomla Article");
    					$this->in_component = 'com_content';
    					return 1;
    				}
    			case 'easyblog.blog':
    				{
    					JvrelInit::debug("Entry Point: Plugin Invokation on EasyBlog Post");
    					$this->in_component = 'com_easyblog';
    					return 1;
    				}
    			case 'com_k2.item':
    				{
    					JvrelInit::debug("Entry Point: Plugin Invokation on K2 Item");
    					$this->in_component = 'com_k2';
    					return 1;
    				}
    			default:
    				{
    					JvrelInit::debug("Invalid Plugin Context: ", $this->context);
    					return 0;
    				}
    		}
    	}
    }

    public function coreProcessor($obj, $article, &$relblock, &$tagblock, &$socialblock)
    {
    	JvrelInit::debug("Starting CoreProcessor...");
    	JvrelInit::debug("Content of displayed article : id: ", $article->id."<>title: ".$article->title);

    	$related_articles = array();
    	$metakeys = $obj->getMetaKeys($article);
    	if (!count($metakeys))
    	{
    		JvrelInit::debug("Metakeywords are not configured for the displayed article. Fallbacking back...");
    		$related_articles = $obj->getFallbackRelatedArticles($article);

    		$this->show_nometakeys_text = 1;
    	}
    	else
    	{
    		JvrelInit::debug("Related Articles Fetch: Normal fetch");
    		$related_articles = $obj->getRelatedArticles($article, $metakeys);
    		if (!count($related_articles))
    		{
    			JvrelInit::debug("No related articles found. Fallback fetch initiated");
    			$related_articles = $obj->getFallbackRelatedArticles($article);
    		}
    	}

    	if (count($related_articles))
    	{
    		$relblock = $this->prepareRelatedArticles($obj, $related_articles);
    		JvrelInit::debug("relblock: ", htmlspecialchars($relblock));

    		// there are related articles/fallback. Something to show. check for eopsob now
    		$this->eopsob->getRelatedArticles($obj, $related_articles);
    	}
    	else
    	{
    		$this->show_norelarticles_text = 1;
    	}

    	if (count($metakeys))
    	{
    		$tagblock = $obj->prepareKeywordTagging($metakeys);
    		JvrelInit::debug("tagblock: ", htmlspecialchars($tagblock));
    	}

    	$socialblock = $obj->prepareSocialMediaGraphics();
    	JvrelInit::debug("socialblock: ", htmlspecialchars($socialblock));

    	$otherblock = '';
    	if (JFactory::getUser()->authorise('core.manage', $this->in_component))
    	{
    		if (($this->show_nometakeys_text) && JvrelInit::getCfg('no_metakeys_text_show'))
    		{
    			$otherblock .= JvrelInit::getCfg('no_metakeys_text')."<br />";
    		}

    		if (($this->show_norelarticles_text) && JvrelInit::getCfg('no_relatives_text_show'))
    		{
    			$otherblock .= JvrelInit::getCfg('no_relatives_text')."<br />";
    		}

    		if ($otherblock != '')
    			JError::raiseWarning('500', $otherblock);
    	}

    	return;
    }

    public function prepareRelatedArticles($obj, $related_articles)
    {
    	$relarts = array();
    	for ($i=0;$i<count($related_articles);$i++)
    	{
    		$relarts[$i] = $this->drawRelatedArticle($obj, $related_articles[$i]);
    	}

    	$tmpl = new JvrelTemplate("tmpl_relblock");
    	$tmpl->component = $obj;
    	$tmpl->ismodule = $obj->isModule();
    	$tmpl->relarts = $relarts;

    	$tmpl_1 = new JvrelTemplate('tmpl_poweredby');
    	$tmpl->poweredby = $tmpl_1->getContents();

    	return $tmpl->getContents();
    }

    private function drawRelatedArticle($obj, $relartobj)
    {
    	// Standard attributes of relartobj - item_id, item_title, item_text, item_createdon, item_createdby, item_modifiedon, item_hits
		JvrelInit::debug("Related article title: ", $relartobj->item_title);

    	// Get target and link
    	$target = (JvrelInit::getCfg('new_window')) ? 'target="_blank"' : '';
    	$href = $obj->getArticleUrl($relartobj);

    	// Get display title
    	$disp_title = (JvrelInit::getCfg('show_title_charcnt')) ? JString::substr($relartobj->item_title, 0, JvrelInit::getCfg('show_title_charcnt'))."..." : JString::substr($relartobj->item_title, 0);

    	// Get link title
    	$atitle = '';
    	if (JvrelInit::getCfg('show_creator'))
    	{
    		$atitle .= JText::_("COM_JVRELATIVES_CREATEDBY").': '.$this->getUsername($relartobj->item_createdby).'<br />';
    	}

    	if (JvrelInit::getCfg('show_timestamp_info') == 0)
    	{
    		$relartobj_created = strftime(JvrelInit::getCfg('timestamp_format'), strtotime($relartobj->item_createdon));
    		$atitle .= JText::_("COM_JVRELATIVES_CREATEDON").': '.$relartobj_created.'<br />';
    	}
    	else if (JvrelInit::getCfg('show_timestamp_info') == 1)
    	{
    		$relartobj_modified = strftime(JvrelInit::getCfg('timestamp_format'), strtotime($relartobj->item_modifiedon));
    		$atitle .= JText::_("COM_JVRELATIVES_LASTUPDATEDON").': '.$relartobj_modified.'<br />';
    	}

    	if (JvrelInit::getCfg('show_hits'))
    	{
    		$atitle .= JText::_("COM_JVRELATIVES_ARTICLE_VIEWS").': '.$relartobj->item_hits.'<br />';
    	}

    	if ($atitle != '')
    	{
    		$atitle = htmlspecialchars($atitle);
    	}

    	if ($obj->isThumbnailEnabled())
    	{
    		$thumburl = JvrelInit::getThumbnailUrl($obj, $relartobj);
    		$media_class = (JvrelInit::getCfg('thumbnail_align')) ? 'media-object-top' : 'media-object';
    	}
    	else
    	{
    		$thumburl = '';
    		$media_class = '';
    	}

    	$tmpl = new JvrelTemplate('tmpl_relart');
    	$tmpl->relartobj = $relartobj;
    	$tmpl->href = $href;
    	$tmpl->target = $target;
    	$tmpl->atitle = $atitle;
    	$tmpl->disp_title = $disp_title;
    	$tmpl->component = $obj;
    	$tmpl->media_class = $media_class;
    	$tmpl->thumburl = $thumburl;
    	return $tmpl->getContents();
    }

    private function loadStyleFiles()
    {
    	JHtml::_('bootstrap.tooltip');

        if (JvrelInit::getCfg("load_bootstrap_css"))
            JHtml::_('bootstrap.loadCss', true);

        if (JvrelInit::getCfg("load_bootstrap_js"))
            JHtml::_('bootstrap.framework');

        $style = JvrelInit::getCfg("style");

        $document = JFactory::getDocument();
        $document->addScript(JUri::root().'media/com_jvrelatives/assets/js/plugin.js');
        $document->addStyleSheet(JUri::root().'media/com_jvrelatives/styles/'.$style.'/style.css');

        if (JFile::exists(JPATH_ROOT.DS."media".DS."com_jvrelatives".DS."styles".DS.$style.DS."custom.css"))
            $document->addStyleSheet(JUri::root().'media/com_jvrelatives/styles/'.$style.'/custom.css');
    }

    private function showJvrelativesOutput($compobj, &$content, $relblock, $tagblock, $socialblock)
    {
    	$this->loadStyleFiles();

    	if (JvrelInit::getCfg('eopsob_only'))
    		return '';

    	$tagblock = ($compobj->isTagEnabled()) ? $tagblock : '';
    	$socialblock = ($compobj->isSocialMediaEnabled()) ? $socialblock : '';

    	if ($this->ismodule)
    	{
			return $relblock . $tagblock . $socialblock;
    	}

    	switch (JvrelInit::getCfg('tag_where'))
    	{
    		case 0: $content = $tagblock.$content; break;
    		case 1: $this->appendBlockToContent($content, $tagblock); break;
    		default: break;
    	}

    	switch (JvrelInit::getCfg('social_where'))
    	{
    		case 0: $content = $socialblock.$content; break;
    		case 1: $this->appendBlockToContent($content, $socialblock); break;
    		default: break;
    	}

    	switch (JvrelInit::getCfg('disp_position'))
    	{
    		case 1:
    		case 2: $content = $relblock.$content; break;
    		case 0: $this->appendBlockToContent($content, $relblock); break;
    		case 4:
    			{
    				$this->replaceJvRelativesTagWith($content, $relblock);
    				JvrelInit::debug("jvrelatives position tag detected and replaced with relblock");
    				break;
    			}
    	}
    	$this->replaceJvRelativesTagWith($content, "");

    	switch (JvrelInit::getCfg('tag_where'))
    	{
    		case 2: $this->appendBlockToContent($content, $tagblock); break;
    		default: break;
    	}

    	switch (JvrelInit::getCfg('social_where'))
    	{
    		case 2: $this->appendBlockToContent($content, $socialblock); break;
    		default: break;
    	}

    	return; // plugin need not return anything
    }

    private function appendBlockToContent(&$content, $block)
    {
    	if (preg_match("/{K2Splitter}/i", $content))
    	{
    		$content = preg_replace("/{K2Splitter}/i", "", $content);
    	}

    	$content .= $block;
    }

    private function replaceJvRelativesTagWith(&$content, $block)
    {
    	if (preg_match("/{jvrelatives}/i", $content))
    	{
    		JvrelInit::debug("{jvrelatives} tag detected in article");
    		$content = preg_replace("/{jvrelatives}/i", $block, $content);
    	}
    }

    private function getUsername($id)
    {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('name')->from('#__users')->where('id = '.(int)$id);
    	$db->setQuery($query);
    	return $db->loadResult();
    }

    private function isComponentDisplayConflict()
    {
    	return ($this->in_component != $this->extn_in_action) ? 1 : 0;
    }
}

class JvrelativesEopsob
{
	public $articles = array();

	public function getRelatedArticles($compobj, $related_articles)
	{
		if (!JvrelInit::getCfg('eopsob_en'))
			return;

		$j=0;
		for ($i=0;$i<count($related_articles);$i++)
		{
			$this->articles[$j]['title'] = $related_articles[$i]->item_title;

			$thumburl = "";
			if (JvrelInit::getCfg('eopsob_show_article_image'))
				$thumburl = JvrelInit::getThumbnailUrl($compobj, $related_articles[$i]);

			$this->articles[$j]['thumburl'] = $thumburl;
			$this->articles[$j]['href'] = $compobj->getArticleUrl($related_articles[$i]);

			$j++;

			if ($j == JvrelInit::getCfg('eopsob_num_articles'))
				break;
		}

		JvrelInit::debug('EOPSOB Relarts', $this->articles);
		return;
	}

	public function setEndofPageSliderText($tagblock, $socialblock)
	{
		if (!JvrelInit::getCfg('eopsob_en'))
			return;

		$width = JvrelInit::getCfg('eopsob_box_width');
		$right = 0 - ($width + 5);

		$tmpl = new JvrelTemplate('tmpl_eopsob');
		$tmpl->articles = $this->articles;
		$tmpl->tagblock = (JvrelInit::getCfg('eopsob_show_tags')) ? $tagblock : '';
		$tmpl->socialblock = (JvrelInit::getCfg('eopsob_show_smedia')) ? $socialblock : '';
		$tmpl->width = $width;
		$tmpl->right = $right;

		$tmpl_1 = new JvrelTemplate('tmpl_poweredby');
		$tmpl->poweredby = $tmpl_1->getContents();
		JvrelInit::setEopsobData($tmpl->getContents());

		JFactory::getDocument()->addScriptDeclaration('
						jQuery(document).ready(function(){
							jQuery(window).scroll(function(){
								var distanceTop = jQuery("#jvrel_last").offset().top - jQuery(window).height();

								if  (jQuery(window).scrollTop() > distanceTop)
									jQuery("#jvrel_slidebox").animate({"right":"0px"},300);
								else
									jQuery("#jvrel_slidebox").stop(true).animate({"right":"'.$right.'%"},100);
							});

							jQuery("#jvrel_slidebox .close").bind("click",function(){
								jQuery(this).parent().remove();
							});
						});
					');

		JvrelInit::debug('EOPSOB Data pushed to memory');
		return;
	}
}