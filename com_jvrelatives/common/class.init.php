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

// Set other definitions
define("_JVREL_LOGPATH", JPATH_ROOT.DS.'components'.DS.'com_jvrelatives'.DS.'temp');
define("_JVREL_ABS_JTMP", JPATH_ROOT.DS.'tmp');
define("_JVREL_ABS_THUMBPATH", JPATH_ROOT.DS.'media'.DS.'com_jvrelatives'.DS.'images'.DS.'thumbs');
define("_JVREL_URL_THUMBPATH", JUri::root().'media/com_jvrelatives/images/thumbs/');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

abstract class JvrelInit
{
	static $jvrel_config = null;
	static $jvrel_eopsob = "";

	public static function initLogs()
	{
		JLog::addLogger(	array(
								'text_entry_format' => '{DATE} {TIME} {CATEGORY} {PRIORITY} {MESSAGE}',
								'text_file' => 'com_jvrelatives.log.php',
								'text_file_path' => _JVREL_LOGPATH,
							),
							JLog::ALL,
							array('com_jvrelatives')
		);
	}

	public static function getCfg($param)
	{
		if (self::$jvrel_config == null)
		{
			try
			{
				$db = JFactory::getDbo();
				$db->setQuery("select * from `#__jvrelatives`");
				self::$jvrel_config = $db->loadObject();
			}
			catch (Exception $ex)
			{
				return '';
			}
		}

		return (isset(self::$jvrel_config->$param)) ? self::$jvrel_config->$param : '';
	}

	public static function mergeCfg($params)
	{
		foreach ($params as $p=>$v)
			self::$jvrel_config->$p = $v;
	}

	public static function debug($text, $var=null)
	{
		if (JvrelInit::getCfg('debug'))
		{
			$msg = $text;

			if ($var != null)
			{
				if ((is_array($var)) || (is_object($var)))
				{
					$msg .= "<pre>".print_r($var, true)."</pre>";
				}
				else
					$msg .= $var;
			}

			JLog::add($msg, JLOG::DEBUG, 'com_jvrelatives');
		}
	}

	public static function getTagParam($param, $tag)
	{
		return (preg_match('/'.preg_quote($param).'=([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/is', $tag, $match)) ? urldecode($match[2]) : '';
	}

	public static function getThumbnailUrl($compobj, $relartobj)
	{
		$w = (int)JvrelInit::getCfg('thumbnail_width');
		$h = (int)JvrelInit::getCfg('thumbnail_height');

		$ofilename = $compobj->component."_".$relartobj->item_id."_".$w.".jpg"; // this is the thumbnail image to be generated

		// does this exist already. if yes return it. if not generate it
		if (JFile::exists(_JVREL_ABS_THUMBPATH.DS.$ofilename))
		{
			$thumburl = _JVREL_URL_THUMBPATH.$ofilename;
			JvrelInit::debug("Thumb already exist in cache. Thumb url: ".$thumburl);
			return $thumburl;
		}

		// thumbnail does not exist already. generate it now.

		// get image from article based on config
		$article_image = $compobj->getImageThumbnail($relartobj);
		JvrelInit::debug("1 - Article image for thumbnail: ".$article_image);

		if ($article_image == '')
		{
			$article_image = (JvrelInit::getCfg('default_thumbnail') != '') ? JUri::root().JvrelInit::getCfg('default_thumbnail') : '';
			JvrelInit::debug("2 - Article image for thumbnail: ".$article_image);
		}

		// fetch the image to /tmp and resize it. return the url of the resized image.
		if ($article_image != '')
		{
			$article_image_2 = JvrelInit::getImage($article_image);
			JvrelInit::debug("getThumbnailUrl getImage Output Filename: ".$article_image_2);
			if ($article_image_2 == "")
			{
				JvrelInit::debug("getImage returned empty string");
				return "";
			}

			$thumbnail_filename = JvrelInit::resize(_JVREL_ABS_JTMP, $article_image_2, _JVREL_ABS_THUMBPATH, $w, $h, $ofilename);
			if ($thumbnail_filename == "")
			{
				JvrelInit::debug("getThumbnailUrl resize returned empty string");
				return "";
			}

			$thumburl = _JVREL_URL_THUMBPATH.$thumbnail_filename;
			JvrelInit::debug("Thumb url: ".$thumburl);
			return $thumburl;
		}
		else
		{
			JvrelInit::debug("Article image is empty. Returning empty thumburl");
			return "";
		}
	}

	public static function getImage($src_pathfile, $dest_path=_JVREL_ABS_JTMP)
	{
		if ((JString::substr($src_pathfile, 0, 7) != 'http://') && (JString::substr($src_pathfile, 0, 8) != 'https://'))
			$src_pathfile = JUri::root().$src_pathfile;

		// Get image file name from source and create a temporary filename from it.
		$tmp_arr = explode("/", $src_pathfile);
		$image_filename = $tmp_arr[count($tmp_arr)-1];

		// Pull the image from remote location
		if (!function_exists('curl_init'))
		{
			JvrelInit::debug("curl not available");
			return '';
		}

		$ch = @curl_init($src_pathfile);
		if ($ch == FALSE)
		{
			JvrelInit::debug("curl init failed");
			return '';
		}

		@curl_setopt($ch, CURLOPT_HEADER, 0);
		@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		@curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
		@curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$resp = @curl_exec($ch);
		@curl_close($ch);

		if ($resp == null)
		{
			JvrelInit::debug("curl returned empty response");
			return '';
		}

		// Save the pulled image to tmp folder
		if (file_put_contents($dest_path.DS.$image_filename, $resp) === false)
		{
			JvrelInit::debug("getImage::file_put_contents failed");
			return '';
		}

		return $image_filename;
	}

	public static function resize($src_imagepath, $src_imagefilename, $ndest_loc, $nwidth, $nheight, $nfilename)
	{
		try
		{
			if (!JFile::exists(JPATH_ROOT.DS.'components'.DS.'com_jvrelatives'.DS.'helpers'.DS.'class.resize_image.php'))
				throw new Exception("Verot does not exist. JV-LD component may not be installed");

			require_once(JPATH_ROOT.DS.'components'.DS.'com_jvrelatives'.DS.'helpers'.DS.'class.resize_image.php');

			JvrelInit::debug('resize: Uploaded file: '.$src_imagepath.DS.$src_imagefilename);

			// copy file to /tmp/subdir
			$t = time().rand(0, 99999);
			if (!JFolder::exists(_JVREL_ABS_JTMP.DS.$t))
				JFolder::create(_JVREL_ABS_JTMP.DS.$t);

			JFile::copy($src_imagepath.DS.$src_imagefilename, _JVREL_ABS_JTMP.DS.$t.DS.$src_imagefilename);

			$handle = new upload(_JVREL_ABS_JTMP.DS.$t.DS.$src_imagefilename);
			$handle->image_resize = true;
			$handle->file_overwrite = false;
			$handle->image_convert = 'jpg';
			$handle->image_x = $nwidth;

			if ($nheight)
				$handle->image_y = $nheight;
			else
				$handle->image_ratio_y = true;

			$handle->file_new_name_body = substr($nfilename, 0, -4);
			$handle->process($ndest_loc);
			$handle->clean();

			@JFolder::delete(_JVREL_ABS_JTMP.DS.$t);
			return $nfilename;
		}
		catch (Exception $ex)
		{
			JvrelInit::debug($ex->getMessage());
			return "";
		}
	}

	public static function setEopsobData($html)
	{
		self::$jvrel_eopsob = $html;
	}

	public static function getEopsobData()
	{
		return self::$jvrel_eopsob;
	}
}

class JvrelTemplate
{
	private $filename = '';
	private $properties = array();

	function __construct($filename)
	{
		$this->filename = $filename;
	}

	function __get($property)
	{
		return $this->properties[$property];
	}

	function __set($property, $value)
	{
		$this->properties[$property] = $value;
	}

	function getContents()
	{
		$style = JvrelInit::getCfg('style');

		ob_start();
		require(JPATH_ROOT.DS.'media'.DS.'com_jvrelatives'.DS.'styles'.DS.$style.DS.'tmpl'.DS.$this->filename.'.php');
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}
}

JvrelInit::initLogs();
JvrelInit::getCfg('init');