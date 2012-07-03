<?php
namespace wcf\acp\form;
use wcf\data\dashboard\box\DashboardBoxList;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\exception\IllegalLinkException;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\WCF;

/**
 * Provides the dashboard option form.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	acp.form
 * @category 	Community Framework
 * 
 * @todo	Add permissions
 */
class DashboardOptionForm extends ACPForm {
	/**
	 * @see	wcf\acp\form\ACPForm::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.dashboard.option';
	
	/**
	 * list of dashboard boxes
	 * @var	wcf\data\dashboard\box\DashboardBoxList
	 */
	public $boxes = array();
	
	/**
	 * dashboard box options
	 * @var	array<array>
	 */
	public $options = array();
	
	/**
	 * list of object types
	 * @var	array<wcf\data\object\type\ObjectType>
	 */
	public $objectTypes = array();
	
	/**
	 * list of object type ids
	 * @var	array<integer>
	 */
	public $objectTypeIDs = array();
	
	/**
	 * @see	wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// load available boxes
		$boxList = new DashboardBoxList();
		$boxList->getConditionBuilder()->add("dashboard_box.packageID IN (?)", array(PackageDependencyHandler::getInstance()->getDependencies()));
		$boxList->sqlLimit = 0;
		$boxList->readObjects();
		$this->boxes = $boxList->getObjects();
		
		// load available object types
		$this->objectTypes = ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.user.dashboardContainer');
		foreach ($this->objectTypes as $objectType) {
			$this->objectTypeIDs[] = $objectType->objectTypeID;
		}
	}
	
	/**
	 * @see	wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['options']) && is_array($_POST['options'])) $this->options = $_POST['options'];
	}
	
	/**
	 * @see	wcf\form\IForm::validate()
	 */
	public function validate() {
		parent::validate();
		
		$this->validateOptions();
	}
	
	/**
	 * Validates dashboard options.
	 */
	protected function validateOptions() {
		foreach ($this->options as $objectTypeID => $boxes) {
			if (!in_array($objectTypeID, $this->objectTypeIDs)) {
				throw new IllegalLinkException();
			}
			
			foreach ($boxes as $boxID => $enabled) {
				if (!isset($this->boxes[$boxID])) {
					throw new IllegalLinkException();
				}
			}
		}
	}
	
	/**
	 * @see	wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (empty($_POST)) {
			$conditions = new PreparedStatementConditionBuilder();
			$conditions->add("objectTypeID IN (?)", array($this->objectTypeIDs));
			$sql = "SELECT	*
				FROM	wcf".WCF_N."_dashboard_option
				".$conditions;
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($conditions->getParameters());
			$boxOptions = array();
			while ($row = $statement->fetchArray()) {
				if (!isset($boxOptions[$row['boxID']])) {
					$boxOptions[$row['boxID']] = array();
				}
				
				$boxOptions[$row['boxID']][$row['objectTypeID']] = $row['enabled'];
			}
			
			foreach ($this->objectTypeIDs as $objectTypeID) {
				$this->options[$objectTypeID] = array();
				
				foreach ($this->boxes as $box) {
					$boxID = $box->boxID;
					
					if (isset($boxOptions[$boxID]) && isset($boxOptions[$boxID][$objectTypeID])) {
						$this->options[$objectTypeID][$boxID] = $boxOptions[$boxID][$objectTypeID];
					}
					else {
						// fallback if data is no available
						$this->options[$objectTypeID][$boxID] = 0;
					}
				}
			}
		}
	}
	
	/**
	 * @see	wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
		
		// remove previous settings
		$conditions = new PreparedStatementConditionBuilder();
		$conditions->add("objectTypeID IN (?)", array($this->objectTypeIDs));
		$sql = "DELETE FROM	wcf".WCF_N."_dashboard_option
			".$conditions;
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($conditions->getParameters());
		
		// insert new settings
		$sql = "INSERT INTO	wcf".WCF_N."_dashboard_option
					(objectTypeID, boxID, enabled)
			VALUES		(?, ?, ?)";
		$statement = WCF::getDB()->prepareStatement($sql);
		
		WCF::getDB()->beginTransaction();
		foreach ($this->objectTypeIDs as $objectTypeID) {
			foreach ($this->boxes as $box) {
				$enabled = 0;
				if (isset($this->options[$objectTypeID][$box->boxID])) {
					$enabled = ($this->options['objectTypeID'][$box->boxID] ? 1 : 0);
				}
				
				$statement->execute(array(
					$objectTypeID,
					$box->boxID,
					$enabled
				));
			}
		}
		WCF::getDB()->commitTransaction();
		
		$this->saved();
		
		WCF::getTPL()->assign('success', true);
	}
	
	/**
	 * @see	wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'boxes' => $this->boxes,
			'objectTypes' => $this->objectTypes,
			'options' => $this->options
		));
	}
}
