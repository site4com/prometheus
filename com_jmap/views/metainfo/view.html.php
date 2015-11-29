<?php
// namespace administrator\components\com_jmap\views\metainfo;
/**
 * @package JMAP::METAINFO::administrator::components::com_jmap
 * @subpackage views
 * @subpackage metainfo
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
 
/**
 * @package JMAP::METAINFO::administrator::components::com_jmap
 * @subpackage views
 * @subpackage metainfo
 * @since 3.2
 */
class JMapViewMetainfo extends JMapView {
	/**
	 * Add the page title and toolbar.
	 */
	protected function addDisplayToolbar() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-jmap{background-image:url("components/com_jmap/images/icon-48-data.png")}');
		JToolBarHelper::title( JText::_( 'COM_JMAP_SITEMAP_METAINFO' ), 'jmap' );
		
		if ($this->user->authorise('core.delete', 'com_jmap') && $this->user->authorise('core.edit', 'com_jmap')) {
			JToolBarHelper::deleteList(JText::_('COM_JMAP_DELETE_ALL_META_DESC'), 'metainfo.deleteEntity', 'COM_JMAP_DELETE_ALL_META');
		}
			
		JToolBarHelper::custom('cpanel.display', 'home', 'home', 'COM_JMAP_CPANEL', false);
	}
	
	/**
	 * Default display listEntities
	 *        	
	 * @access public
	 * @param string $tpl
	 * @return void
	 */
	public function display($tpl = null) {
		// Tooltip for locked record
		JHTML::_('behavior.tooltip');
		
		// Get main records
		$rows = $this->get ( 'Data' );
		$lists = $this->get ( 'Filters' );
		$total = $this->get ( 'Total' );
		
		$doc = JFactory::getDocument();
		$this->loadJQuery($doc);
		$this->loadBootstrap($doc);
		$doc->addScript ( JURI::root ( true ) . '/administrator/components/com_jmap/js/metainfo.js' );
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jmap/css/metainfo.css' );
		
		// Inject js translations
		$translations = array (
				'COM_JMAP_METAINFO_TITLE',
				'COM_JMAP_METAINFO_PROCESS_RUNNING',
				'COM_JMAP_METAINFO_STARTED_SITEMAP_GENERATION',
				'COM_JMAP_METAINFO_ERROR_STORING_FILE',
				'COM_JMAP_METAINFO_GENERATION_COMPLETE',
				'COM_JMAP_METAINFO_ANALYZING_LINKS',
				'COM_JMAP_METAINFO_ERROR_STORING_DATA',
				'COM_JMAP_METAINFO_SET_ATLEAST_ONE',
				'COM_JMAP_METAINFO_SAVED',
				'COM_JMAP_CHARACTERS'
		);
		$this->injectJsTranslations($translations, $doc);
						
		$orders = array ();
		$orders ['order'] = $this->getModel ()->getState ( 'order' );
		$orders ['order_Dir'] = $this->getModel ()->getState ( 'order_dir' );
		// Pagination view object model state populated
		$pagination = new JPagination ( $total, $this->getModel ()->getState ( 'limitstart' ), $this->getModel ()->getState ( 'limit' ) );
		
		$this->user = JFactory::getUser ();
		$this->pagination = $pagination;
		$this->searchword = $this->getModel ()->getState ( 'searchword' );
		$this->lists = $lists;
		$this->orders = $orders;
		$this->items = $rows;
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
		
		parent::display ( 'list' );
	}
}