<?php
/**
 * @package		JV-Relatives
 * @subpackage	com_jvrelatives
 * @copyright	Copyright 2008-2013 JV-Extensions. All rights reserved
 * @license		GNU General Public License version 3 or later
 * @author		JV-Extensions
 * @link		http://www.jv-extensions.com
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JvrelativesViewAjax extends JViewLegacy
{
    function display($tpl = null)
    {
	    $layout = JFactory::getApplication()->input->getString('layout', '');
   		switch ($layout)
   		{
   			case 'getlatversion': $this->getLatestVersion(); break;
   			case 'getlatestnews': $this->getLatestNews(); break;
			case 'install': $this->installJvrelatives(); break;
   			default: echo ''; break;
   		}    		
    	    	 
    	JFactory::getApplication()->close();
    }
    
    private function getLatestVersion()
    { 
		if (!function_exists('curl_init'))
    		echo '';

    	$ch = @curl_init("http://www.jv-extensions.com/versioncheck.php?pid=jvrel&j=".JVERSION);
    	if ($ch == FALSE) 
    		echo '';
    		
    	@curl_setopt($ch, CURLOPT_HEADER, 0);
    	@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	@curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
    	@curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    	@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    	@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	$resp = @curl_exec($ch);
    	@curl_close($ch);

    	$tokens = explode("|",$resp);
    	echo JString::trim($tokens[1]);
    }
    
    private function getLatestNews() 
    {
    	$out = '';    
    	if (class_exists("DOMDocument"))
    	{
    		$arrFeeds = array();
    		
    		$doc = new DOMDocument();
    		$doc->load("http://www.jv-extensions.com/blog/feed");
    		$cnt=0;
    		foreach ($doc->getElementsByTagName('item') as $node) 
    		{
    			$itemRSS = array (
    					'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
    					'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
    					'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
    					'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue
    			);
    			array_push($arrFeeds, $itemRSS);
    			$cnt++;
    			if ($cnt == 5)
    				break;
    		}
    		
    		$out .= '<ul class="nav nav-tabs nav-stacked">';
    		for ($i=0;$i<count($arrFeeds);$i++)
    		{
    			$out .= '<li>
    						<a href="'.$arrFeeds[$i]['link'].'" target="_blank">
    							<small>'.$arrFeeds[$i]['date'].'</small><br />'.$arrFeeds[$i]['title'].'
    			    	    </a>
    			    	 </li>';
    		}    		
    		$out .= '</ul>';
    	}
    	
    	echo $out;
    }

    private function installJvrelatives()
    {
    	$out = '';
    
    	$ma_version = JvrelInstallerHelper::getVersionFromManifest();
    	$db_version = JvrelInstallerHelper::getDbVersion();   

    	try
    	{
    		if (0 == $db_version)
    		{
    			$installer = JvrelInstallerHelper::getInstallerObject("0.0");
    			$installer->execute();
    			if ($installer->getErrorFlag())
    				throw new Exception($installer->getMessage());
    
    			JvrelInstallerHelper::updateVersionInDb($ma_version);    
    			$out .= '0|0|100|'.$installer->getMessage();
    		}
    		else
    		{
    			$sindex = $tindex =  0;
    			$versions_to_upgrade = JvrelInstallerHelper::getVersionsUpgradeList($db_version, $sindex, $tindex);
    
    			if (0 == count($versions_to_upgrade))
    			{
    				$out .= '0|0|100|Upgrade complete';
    			}
    			else
    			{
    				$installer = JvrelInstallerHelper::getInstallerObject($versions_to_upgrade[0]);
    				$retval = $installer->execute();
    				if ($installer->getErrorFlag())
    					throw new Exception($installer->getMessage());
    				
    				if (($retval == -99) || ($retval == -1)) // non-sideloader or sideloader complete
    				{
    					JvrelInstallerHelper::updateVersionInDb($versions_to_upgrade[0]);
    					
    					if (1 == count($versions_to_upgrade))
    					{
    						$out .= '0|0|100|'.$installer->getMessage();
    					}
    					else
    					{
    						$percent_complete = intval((1 + $sindex)*100/(1 + $tindex + 1));
    						$percent_complete = ($percent_complete > 100) ? 100 : $percent_complete;    					
    						$out .= $versions_to_upgrade[1].'|0|'.$percent_complete.'|'.$installer->getMessage();
    					}
    				}
    				else // sideloader continues
    				{
    					$percent_complete = intval((1 + $sindex)*100/(1 + $tindex + 1));
    					$percent_complete = ($percent_complete > 100) ? 100 : $percent_complete;    				
    					$out .= $versions_to_upgrade[0].'|0|'.$percent_complete.'|'.$installer->getMessage();
    				}    				
    			}
    		}
    	}
    	catch (Exception $ex)
    	{
    		$out .= '0|1|100|<p class="alert alert-error">'.$ex->getMessage().'</p>';
    	}
    
    	$out = JString::trim($out, "<br />");
    	$out .= "<br />";
    	echo $out; // {next_version_to_upgrade|error flag|percent complete|message}
    }          
}