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

jimport("joomla.filesystem.file");
jimport("joomla.filesystem.folder");

abstract class JvrelInstallerHelper
{
	public static function getDbVersion()
	{
		try {
			$db = JFactory::getDbo();
			$db->setQuery("select * from `#__jvrelatives`");
			$obj = $db->loadObject();
			if ($obj)
			{
				if (isset($obj->version))
				{
					$v = JString::trim($obj->version);
					if (JString::strpos($v, "Build") !== false)
						return JString::str_ireplace(" Build-", ".", $v);

					return $v;
				}
				else
				{
					$db->setQuery("drop table if exists #__jvrelatives");
					$db->execute();

					return 0;
				}
			}

			return 0;
		}
		catch (Exception $ex) {
			//echo $ex->getMessage();
			return 0;
		}
	}
	public static function getVersionFromManifest()
	{
		$db = JFactory::getDbo();
		$db->setQuery("select manifest_cache from #__extensions where element = 'com_jvrelatives' and type = 'component'");
		$tmp_version = json_decode($db->loadResult());
		return JString::trim($tmp_version->version);
	}
	public static function getVersionsUpgradeList($db_version, &$sindex, &$tindex)
	{
		$versions = array();
		$files = JFolder::files(JPATH_ADMINISTRATOR.DS."components".DS."com_jvrelatives".DS."setup");
		for ($i=0;$i<count($files);$i++)
			$versions[$i] = JString::trim(basename($files[$i], ".php"));

		usort($versions, "version_compare");

		$key = array_search($db_version, $versions);
		if ($key === false)
			throw new Exception("Current DB Version [".$db_version."] is not available in the list of available versions [".print_r($versions, true)."]");

		$key++;
		$sindex = $key;
		$tindex = count($versions);

		$upgrade_versions = array();
		for ($k=0,$i=$key;$i<count($versions);$i++)
			$upgrade_versions[$k++] = $versions[$i];

		return $upgrade_versions;
	}
	public static function getInstallerObject($version)
	{
		$filename = JPATH_ADMINISTRATOR.DS."components".DS."com_jvrelatives".DS."setup".DS.$version.".php";
		if (!JFile::exists($filename))
			throw new Exception("Installer PHP file [".$filename."] does not exist");

		require_once($filename);
		$clsname = "JvrelVersionInstaller_".JString::str_ireplace(".", "_", $version);
		return new $clsname($version);
	}
	public static function updateVersionInDb($version)
	{
		$db = JFactory::getDbo();
		$db->setQuery("update `#__jvrelatives` set `version` = '".$db->escape($version)."'");
		$db->execute();
	}
}

abstract class JvrelVersionInstaller
{
	var $version;
	var $dversion;
	var $sqls = array();
	var $msgs = array();
	var $error;

	// Sideload Params
	var $sl;
	var $sl_start;
	var $sl_maxrec;
	var $sl_total;
	var $sl_allcnt;
	var $sl_msgs;

	function __construct($version, $sqls=array())
	{
		$this->sl = 0;
		$this->sl_start = 0;
		$this->sl_maxrec = 500;
		$this->sl_total = 0;
		$this->sl_allcnt = 0;

		$this->error = 0;
		$this->version = $version;
		$this->sqls = $sqls;
		$this->dversion = ($this->version == "0.0") ? JvrelInstallerHelper::getVersionFromManifest() : $this->version;
		$this->add2DebugLog("Installing Component Version: ".$this->dversion, 2);
	}
	function execPreDb()
	{
		return;
	}
	function execPostDb()
	{
		return;
	}
	function execSql()
	{
		$db = JFactory::getDbo();
		for ($i=0;$i<count($this->sqls);$i++)
		{
			$this->add2DebugLog("Query to run: ".$this->sqls[$i], 0, 0);

			$db->setQuery($this->sqls[$i]);
			if (!$db->execute())
				throw new Exception("Sql Error for [".$this->sqls[$i]."]: ".$db->getErrorMsg());
		}

		$this->add2DebugLog(count($this->sqls)." Sql queries have been run successfully", 0, 0);
		return;
	}
	function execute()
	{
		try
		{
			if ($this->sl)
			{
				$sls = JFactory::getApplication()->input->getInt('sls', 0);
				if ($sls != -1)
				{
					$this->slSetStart($sls);
					$processed = $this->runSideloader();

					$this->sl_total = $this->sl_start + $processed;
					$this->add2DebugLog("Processed ".$this->sl_total." out of ".$this->sl_allcnt." entries till now...");

					if ($this->slIsDone())
					{
						$sls = -1;
						$this->add2DebugLog("Installation to version : ".$this->dversion." completed", 1);
					}
					else
						$sls = $sls + $processed;
				}
				return $sls;
			}
			else
			{
				$this->execPreDb();
				$this->execSql();
				$this->execPostDb();

				$this->add2DebugLog("Installation to version : ".$this->dversion." completed", 1);
			}

		}
		catch (Exception $ex)
		{
			$this->error = 1;
			$this->add2DebugLog("Error while running installation to version : ".$this->dversion." >> ".$ex->getMessage(), -1);
		}

		return -99;
	}

	// Sideload Methods
	function setSideLoad()
	{
		$this->sl = 1;
	}
	function slSetStart($start)
	{
		$this->sl_start = $start;
	}
	function slIsDone()
	{
		return ($this->sl_total >= $this->sl_allcnt) ? 1 : 0;
	}
	function runSideloader()
	{
		return;
	}

	// Utility Methods
	function add2DebugLog($msg, $type=0, $show_msg=1)
	{
		switch ($type)
		{
			case -1: $msg = '<span class="jvext_error">'.$msg.'</span>'; break;
			case 1: $msg = '<span class="jvext_ok">'.$msg.'</span>'; break;
			case 2: $msg = '<span class="jvext_title">'.$msg.'</span>'; break;
			default:  break;
		}

		$install_dbg = JPATH_ROOT.DS."media".DS."com_jvrelatives".DS."temp".DS."com_jvrelatives.install.html";
		if (!JFile::exists($install_dbg)) {
			$fp = fopen($install_dbg, "w");
			fwrite($fp, "");
			fwrite($fp, "	<style type='text/css'>
								span.jvext_error {
									color: red;
								}
								span.jvext_ok {
									color: green;
								}
								span.jvext_title {
									font-weight:bold;
									color:#363636;
								}
							</style>
					");
		}
		else {
			$fp = fopen($install_dbg, "a+");
		}

		$tnow = date("Y-m-d H:i:s", time());
		fwrite($fp, $tnow." ".$msg."<br />");
		fclose($fp);

		if ($show_msg)
			$this->msgs[] = $msg;
	}
	function getMessage()
	{
		return implode("<br />", $this->msgs);
	}
	function getErrorFlag()
	{
		return $this->error;
	}
}