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
  
class JvrelativesCache 
{
	var $header;
    var $filename;
    var $cache_lifetime;
    
    public function __construct($component, $article_id, $cache_lifetime) 
    {
    	$this->header = "<?php defined('_JEXEC') or die('Restricted access'); ?>";
    	$this->cache_lifetime = $cache_lifetime;
        $this->filename = JPATH_ADMINISTRATOR.DS."components".DS."com_jvrelatives".DS."cache".DS."cache_".$component."_".(int)$article_id.".php";
    }
    
    public function isAvailable() 
    {
        return (file_exists($this->filename)) ? 1 : 0;
    }
    
    public function isExpired() 
    {
        $last_mod_time = filemtime($this->filename);
        if ($last_mod_time == FALSE)
            return 1;
        
        $diff = ceil((time() - $last_mod_time)/60);
        if ($diff > $this->cache_lifetime) 
            return 1;
        
        return 0;        
    }
    
    function store($relblock, $tagblock) 
    {    	
    	$dataobj = array('relblock' => $relblock, 'tagblock' => $tagblock);
    	$ser_dataobj = serialize($dataobj);
    	file_put_contents($this->filename, $this->header.$ser_dataobj);
    	return;
    }
    
    function load(&$relblock, &$tagblock) {
    	if (file_exists($this->filename)) 
    	{
    		$dataobj = array();
    		$ser_dataobj_tmp = file_get_contents($this->filename);
    		$ser_dataobj = JString::substr($ser_dataobj_tmp, JString::strlen($this->header));
    		$dataobj = unserialize($ser_dataobj);
    		
    		$relblock = $dataobj['relblock'];
    		$tagblock = $dataobj['tagblock'];
    	}
    	return;
    }    
}