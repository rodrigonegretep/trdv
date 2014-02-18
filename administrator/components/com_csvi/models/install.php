<?php
/**
 * Install model
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: install.php 2476 2013-07-26 15:33:57Z Roland $
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.model' );

/**
 * Install Model
 *
 * @package CSVI
 */
class CsviModelInstall extends JModel {

	/** */
	private $_templates = array();
	private $_tag = '';
	private $_results = array();
	private $_tables = array();

	/**
	 * Find the version installed
	 *
	 * Version 4 is the first version
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		Check version from database
	 * @todo		Convert settings from INI format to JSON format
	 * @see
	 * @access 		private
	 * @param
	 * @return 		string	the version determined by the database
	 * @since 		3.0
	 */
	public function getVersion() {
		// Determine the tables in the database
		$version = $this->_getVersion();
		if (empty($version)) $version = 'current';
		return $version;
	}

	/**
	 * Start performing the upgrade
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		string	the result of the upgrade
	 * @since 		3.0
	 */
	public function getUpgrade() {
		// Get the currently installed version
		$version = $this->_translateVersion();

		// Migrate the data in the tables
		if ($this->_migrateTables($version)) $this->_results['messages'][] = JText::_('COM_CSVI_UPGRADE_OK');

		// Update the version number in the database
		$this->_setVersion();

		// Load the components
		$this->_loadComponents();

		// Send the results back
		return $this->_results;
	}

	/**
	 * Migrate the tables
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$version	the version being migrated from
	 * @return 		bool	true if migration is OK | false if errors occured during migration
	 * @since 		3.0
	 */
	private function _migrateTables($version) {
		$db = JFactory::getDbo();
		switch ($version) {
			case '4.0':
				break;
			default:
				break;
		}
	}

	/**
	 * Proxy function for calling the update the available fields
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getAvailableFields() {
		// Get the logger class
		$jinput = JFactory::getApplication()->input;
		$csvilog = new CsviLog();
		$jinput->set('csvilog', $csvilog);
		$model = $this->getModel('Availablefields');
		// Prepare to load the available fields
		$model->prepareAvailableFields();

		// Update the available fields
		$model->getFillAvailableFields();
	}

	/**
	 * Proxy function for installing sample templates
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getSampleTemplates() {
		$db = JFactory::getDbo();
		// Read the example template file
		$fp = fopen(JPATH_COMPONENT_ADMINISTRATOR.'/install/example_templates.csv', "r");
		if ($fp) {
			while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
				$db->setQuery("INSERT IGNORE INTO #__csvi_template_settings (".$db->quoteName('name').", ".$db->quoteName('settings').")
													VALUES (".$db->Quote($data[0]).", ".$db->Quote($data[1]).")");
				if ($db->query()) {
					$this->_results['messages'][] = JText::sprintf('COM_CSVI_RESTORE_TEMPLATE', $data[0]);
				}
				else {
					$this->_results['messages'][] = $db->getErrorMsg();
					$this->_results['messages'][] = JText::sprintf('COM_CSVI_COMPONENT_HAS_NOT_BEEN_ADDED', $file);
				}
			}
			fclose($fp);
		}
	}

	/**
	 * Create a proxy for including other models
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return
	 * @since 		3.0
	 */
	protected function getModel($model) {
		return $this->getInstance($model, 'CsviModel');
	}

	/**
	 * Set the current version in the database
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.1
	 */
	private function _setVersion() {
		$db = JFactory::getDbo();
		$q = "INSERT IGNORE INTO #__csvi_settings (id, params) VALUES (2, '".JText::_('COM_CSVI_CSVI_VERSION')."')
			ON DUPLICATE KEY UPDATE params = '".JText::_('COM_CSVI_CSVI_VERSION')."'";
		$db->setQuery($q);
		$db->query();
	}

	/**
	 * Get the current version in the database
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.2
	 */
	private function _getVersion() {
		$db = JFactory::getDbo();
		$q = "SELECT params
			FROM #__csvi_settings
			WHERE id = 2";
		$db->setQuery($q);
		return $db->loadResult();
	}

	/**
	 * Translate version
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return 		string with the working version
	 * @since 		3.5
	 */
	private function _translateVersion() {
	$jinput = JFactory::getApplication()->input;
		$version = $jinput->get('version', 'current', 'string');
		switch ($version) {
			case '4.0.1':
			case '4.1':
			case '4.2':
				return '4.0';
				break;
			default:
				return $version;
				break;
		}
	}

	/**
	 * Load supported components
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		4.0
	 */
	private function _loadComponents() {
		$db = JFactory::getDbo();
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$files = JFolder::files(JPATH_COMPONENT_ADMINISTRATOR.'/install', '.sql', false, false, array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'availablefields_extra.sql'));
		if (!empty($files)) {
			foreach ($files as $file) {
				$error = false;
				if (JFile::exists(JPATH_COMPONENT_ADMINISTRATOR.'/install/'.$file)) {
					$q = JFile::read(JPATH_COMPONENT_ADMINISTRATOR.'/install/'.$file);
					$queries = $db->splitSql(JFile::read(JPATH_COMPONENT_ADMINISTRATOR.'/install/'.$file));
					foreach ($queries as $query) {
						$query = trim($query);
						if (!empty($query)) {
							$db->setQuery($query);
							if (!$db->query()) {
								$this->_results['messages'][] = $db->getErrorMsg();
								$error = true;
							}
						}
					}
					if ($error) $this->_results['messages'][] = JText::sprintf('COM_CSVI_COMPONENT_HAS_NOT_BEEN_ADDED', $file);
					else $this->_results['messages'][] = JText::sprintf('COM_CSVI_COMPONENT_HAS_BEEN_ADDED', $file);
				}
				else $this->_results['messages'][] = JText::sprintf('COM_CSVI_COMPONENT_NOT_FOUND', $file);
			}
		}
	}
}
?>