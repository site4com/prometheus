<?php
// namespace administrator\components\com_jmap\controllers;
/**
 * @package JMAP::GOOGLE::administrator::components::com_jmap
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Main controller
 * @package JMAP::GOOGLE::administrator::components::com_jmap
 * @subpackage controllers
 * @since 3.1
 */
class JMapControllerGoogle extends JMapController {
	/**
	 * Set model state from session userstate
	 * @access protected
	 * @param string $scope
	 * @return void
	 */
	protected function setModelState($scope = 'default', $ordering = true) {
		$option = $this->option;
		// Get default model
		$defaultModel = $this->getModel();
		// Set model state
		$defaultModel->setState ( 'option', $option );
		return $defaultModel;
	}
	
	/**
	 * Default listEntities
	 * 
	 * @access public
	 * @param $cachable string
	 *       	 the view output will be cached
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) {
		// Access check.
		if (!$this->user->authorise ( 'jmap.google', $this->option )) {
			$this->setRedirect('index.php?option=com_jmap&task=cpanel.display', JTEXT::_('COM_JMAP_ERROR_ALERT_NOACCESS'));
			return false;
		}
		
		// Composer autoloader
		require_once JPATH_COMPONENT_ADMINISTRATOR. '/framework/composer/autoload_real.php';
		ComposerAutoloaderInitfc5c9af51413a149e4084a610a3ab6de::getLoader();
		
		$this->setModelState('google');
		parent::display($cachable, $urlparams);
	}
	
	/**
	 * Delete a db table entity
	 *
	 * @access public
	 * @return void
	 */
	public function deleteEntity() {
		// Load della model e checkin before exit
		$model = $this->getModel ();
	
		if (! $model->deleteEntity ( null )) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelException = $model->getError ( null, false );
			$this->app->enqueueMessage ( $modelException->getMessage (), $modelException->getErrorLevel () );
			$this->setRedirect ( "index.php?option=" . $this->option . "&task=" . $this->corename . ".display", JText::_ ( 'COM_JMAP_GOOGLE_ERROR_LOGOUT' ) );
			return false;
		}
	
		$this->setRedirect ( "index.php?option=" . $this->option . "&task=" . $this->corename . ".display", JText::_ ( 'COM_JMAP_GOOGLE_SUCCESS_LOGOUT' ) );
	}
}