<?php
namespace wcf\acp\page;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\package\PackageList;
use wcf\page\AbstractPage;
use wcf\system\menu\acp\ACPMenu;
use wcf\system\WCF;

/**
 * Provides a list of registered dashboard pages.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	acp.page
 * @category 	Community Framework
 */
class DashboardListPage extends AbstractPage {
	/**
	 * @see	wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.content.dashboard.canEditOption');
	
	/**
	 * list of object types per package id
	 * @var	array<array>
	 */
	public $objectTypes = array();
	
	/**
	 * list of packages
	 * @var	wcf\data\package\PackageList
	 */
	public $packageList = null;
	
	/**
	 * @see	wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		// load object types
		$objectTypes = ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.user.dashboardContainer');
		$packageIDs = array();
		foreach ($objectTypes as $objectType) {
			if (!isset($this->objectTypes[$objectType->packageID])) {
				$this->objectTypes[$objectType->packageID] = array();
			}
			
			$this->objectTypes[$objectType->packageID][] = $objectType;
		}
		
		// load packages
		$this->packageList = new PackageList();
		$this->packageList->getConditionBuilder()->add("package.packageID IN (?)", array(array_keys($this->objectTypes)));
		$this->packageList->sqlLimit = 0;
		$this->packageList->readObjects();
	}
	
	/**
	 * @see	wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'objectTypes' => $this->objectTypes,
			'packageList' => $this->packageList
		));
	}
	
	/**
	 * @see wcf\page\IPage::show()
	 */
	public function show() {
		// enable menu item
		ACPMenu::getInstance()->setActiveMenuItem('wcf.acp.menu.link.dashboard.list');
	
		parent::show();
	}
}
