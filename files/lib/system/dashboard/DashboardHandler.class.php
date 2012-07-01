<?php
namespace wcf\system\dashboard;
use wcf\data\object\type\ObjectTypeCache;
use wcf\page\IPage;
use wcf\system\application\ApplicationHandler;
use wcf\system\cache\CacheHandler;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\exception\SystemException;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\SingletonFactory;
use wcf\system\WCF;
use wcf\util\ClassUtil;

/**
 * Handles dashboard boxes.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.dashboard
 * @category 	Community Framework
 */
class DashboardHandler extends SingletonFactory {
	/**
	 * list of cached dashboard boxes
	 * @var	array<wcf\data\dashboard\box\DashboardBox>
	 */
	protected $boxCache = null;
	
	/**
	 * configuration options for pages
	 * @var	array<array>
	 */
	protected $pageCache = null;
	
	/**
	 * @see	wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		$primaryApplication = ApplicationHandler::getInstance()->getPrimaryApplication();
		$cacheName = 'dashboardBoxes-'.$primaryApplication->packageID;
		
		CacheHandler::getInstance()->addResource(
			$cacheName,
			WCF_DIR.'cache/cache.'.$cacheName.'.php',
			'wcf\system\cache\builder\DashboardBoxCacheBuilder'
		);
		$this->boxCache = CacheHandler::getInstance()->get($cacheName, 'boxes');
		$this->pageCache = CacheHandler::getInstance()->get($cacheName, 'pages');
	}
	
	/**
	 * Returns active dashboard boxes for given object type id.
	 * 
	 * @param	string		$objectType
	 * @param	wcf\page\IPage	$page
	 */
	public function loadBoxes($objectType, IPage $page) {
		$objectType = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.user.dashboardContainer', $objectType);
		if ($objectType === null) {
			throw new SystemException("Unable to find object type '".$objectType."' for definition 'com.woltlab.wcf.user.dashboardContainer'");
		}
		
		$boxIDs = array();
		foreach ($this->pageCache[$objectType->objectType] as $boxID => $enabled) {
			if ($enabled) {
				$boxIDs[] = $boxID;
			}
		}
		
		// no active boxes found, abort
		if (empty($boxIDs)) {
			return;
		}
		
		$contentTemplate = $sidebarTemplate = '';
		foreach ($boxIDs as $boxID) {
			$className = $this->boxCache[$boxID]->className;
			if (!ClassUtil::isInstanceOf($className, 'wcf\system\dashboard\box\IDashboardBox')) {
				throw new SystemException("Box '".$this->boxCache[$boxID]->boxName."' does not implement 'wcf\system\dashboard\box\IDashboardbox'");
			}
			
			$boxObject = new $className();
			$boxObject->init($this->boxCache[$boxID], $page);
			
			if ($this->boxCache[$boxID]->boxType == 'content') {
				$contentTemplate .= $boxObject->getTemplate();
			}
			else {
				$sidebarTemplate .= $boxObject->getTemplate();
			}
		}
		
		WCF::getTPL()->assign(array(
			'__boxContent' => $contentTemplate,
			'__boxSidebar' => $sidebarTemplate
		));
	}
	
	/**
	 * Sets default values upon installation, you should not call this method
	 * under any other circumstances. If you do not specify a list of box names,
	 * all boxes will be assigned as disabled for given object type.
	 * 
	 * @param	string		$objectType
	 * @param	array<names>	$enableBoxNames
	 */
	public static function setDefaultValues($objectType, array $enableBoxNames = array()) {
		$objectTypeID = 0;
		
		// get object type id (cache might be outdated)
		if (PACKAGE_ID) {
			// reset object type cache
			// TODO: Add a method to ObjectType in order to clear cache
			CacheHandler::getInstance()->clear(WCF_DIR.'cache/', 'cache.objectType-*.php');
			
			// get object type
			$objectTypeObj = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.user.dashboardContainer', $objectType);
			if ($objectTypeObj === null) {
				throw new SystemException("Object type '".$objectType."' is not valid for definition 'com.woltlab.wcf.user.dashboardContainer'");
			}
			
			$objectTypeID = $objectTypeObj->objectTypeID;
		}
		else {
			// work-around during WCFSetup
			$conditions = new PreparedStatementConditionBuilder();
			$conditions->add("object_type.objectType = ?", array($objectType));
			$conditions->add("object_type_definition.definitionName = ?", array('com.woltlab.wcf.user.dashboardContainer'));
			
			$sql = "SELECT		object_type.objectTypeID
				FROM		wcf1_object_type object_type
				LEFT JOIN	wcf1_object_type_definition object_type_definition
				ON		(object_type_definition.definitionID = object_type.definitionID)
				".$conditions;
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($conditions->getParameters());
		}
		
		// select available box ids
		$conditions = new PreparedStatementConditionBuilder();
		$conditions->add("packageID IN (?)", array(PackageDependencyHandler::getInstance()->getDependencies()));
		
		$sql = "SELECT	boxID, boxName
			FROM	wcf".WCF_N."_dashboard_box
			".$conditions;
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($conditions->getParameters());
		
		$boxes = array();
		while ($row = $statement->fetchArray()) {
			if (in_array($row['boxName'], $enableBoxNames)) {
				$boxes[$row['boxID']] = 1;
			}
			else {
				$boxes[$row['boxID']] = 0;
			}
		}
		
		if (!empty($boxes)) {
			// remove previous settings
			$conditions = new PreparedStatementConditionBuilder();
			$conditions->add("objectTypeID = ?", array($objectType->objectTypeID));
			$conditions->add("boxID IN (?)", array(array_keys($boxes)));
			
			$sql = "DELETE FROM	wcf".WCF_N."_dashboard_option
				".$conditions;
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($conditions->getParameters());
			
			// insert associations
			$sql = "INSERT INTO	wcf".WCF_N."_dashboard_option
						(objectTypeID, boxID, enabled)
				VALUES		(?, ?, ?)";
			$statement = WCF::getDB()->prepareStatement($sql);
			
			WCF::getDB()->beginTransaction();
			foreach ($boxes as $boxID => $enabled) {
				$statement->execute(array(
					$objectType->objectTypeID,
					$boxID,
					$enabled
				));
			}
			WCF::getDB()->commitTransaction();
		}
	}
}
