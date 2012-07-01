<?php
namespace wcf\system\dashboard;
use wcf\data\object\type\ObjectTypeCache;
use wcf\page\IPage;
use wcf\system\application\ApplicationHandler;
use wcf\system\cache\CacheHandler;
use wcf\system\exception\SystemException;
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
}
