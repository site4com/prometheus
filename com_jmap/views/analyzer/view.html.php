<?php
// namespace administrator\components\com_jmap\views\analyzer;
/**
 * @package JMAP::ANALYZER::administrator::components::com_jmap
 * @subpackage views
 * @subpackage analyzer
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
 
/**
 * @package JMAP::ANALYZER::administrator::components::com_jmap
 * @subpackage views
 * @subpackage analyzer
 * @since 2.3.3
 */
class JMapViewAnalyzer extends JMapView {
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-jmap{background-image:url("components/com_jmap/images/icon-48-data.png")}');
		JToolBarHelper::title( JText::_( 'COM_JMAP_SITEMAP_ANALYZER' ), 'jmap' );
			
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
		$doc->addScript ( JURI::root ( true ) . '/administrator/components/com_jmap/js/analyzer.js' );
		
		// Inject js translations
		$translations = array (
				'COM_JMAP_ANALYZER_TITLE',
				'COM_JMAP_ANALYZER_PROCESS_RUNNING',
				'COM_JMAP_ANALYZER_STARTED_SITEMAP_GENERATION',
				'COM_JMAP_ANALYZER_ERROR_STORING_FILE',
				'COM_JMAP_ANALYZER_GENERATION_COMPLETE',
				'COM_JMAP_ANALYZER_ANALYZING_LINKS' );
		$this->injectJsTranslations($translations, $doc);
						
		$orders = array ();
		$orders ['order'] = $this->getModel ()->getState ( 'order' );
		$orders ['order_Dir'] = $this->getModel ()->getState ( 'order_dir' );
		// Pagination view object model state populated
		$pagination = new JPagination ( $total, $this->getModel ()->getState ( 'limitstart' ), $this->getModel ()->getState ( 'limit' ) );
		
		$this->user = JFactory::getUser ();
		$this->pagination = $pagination;
		$this->link_type = $this->getModel ()->getState ('link_type', null);
		$this->lists = $lists;
		$this->orders = $orders;
		$this->items = $rows;
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
		
		parent::display ( 'list' );
	}
}