<?php
namespace wcf\system\menu\user\profile;
use wcf\data\user\profile\menu\item\UserProfileMenuItem;
use wcf\system\cache\CacheHandler;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * Builds the user profile menu.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.menu.user.profile
 * @category 	Community Framework
 */
class UserProfileMenu extends SingletonFactory {
	/**
	 * list of all menu items
	 * @var array<wcf\system\menu\ITreeMenuItem>
	 */
	public $menuItems = null;
	
	/**
	 * @see	wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		// get menu items from cache
		$this->loadCache();
		
		// check menu items
		$this->checkMenuItems();
		
		// call init event
		EventHandler::getInstance()->fireAction($this, 'init');
	}
	
	/**
	 * Loads cached menu items.
	 */
	protected function loadCache() {
		// call loadCache event
		EventHandler::getInstance()->fireAction($this, 'loadCache');
		
		$cacheName = 'userProfileMenu-'.PACKAGE_ID;
		CacheHandler::getInstance()->addResource(
			$cacheName,
			WCF_DIR.'cache/cache.'.$cacheName.'.php',
			'wcf\system\cache\builder\UserProfileMenuCacheBuilder'
		);
		$this->menuItems = CacheHandler::getInstance()->get($cacheName);
	}
	
	/**
	 * Checks the options and permissions of the menu items.
	 */
	protected function checkMenuItems() {
		foreach ($this->menuItems as $key => $item) {
			if (!$this->checkMenuItem($item)) {
				// remove this item
				unset($this->menuItems[$key]);
			}
		}
	}
	
	/**
	 * Checks the options and permissions of given menu item.
	 * 
	 * @param	wcf\data\user\profile\menu\item\UserProfileMenuItem	$item
	 * @return	boolean
	 */
	protected function checkMenuItem(UserProfileMenuItem $item) {
		// check the options of this item
		$hasEnabledOption = true;
		if (!empty($item->options)) {
			$hasEnabledOption = false;
			$options = explode(',', strtoupper($item->options));
			foreach ($options as $option) {
				if (defined($option) && constant($option)) {
					$hasEnabledOption = true;
					break;
				}
			}
		}
		if (!$hasEnabledOption) return false;
		
		// check the permission of this item for the active user
		$hasPermission = true;
		if (!empty($item->permissions)) {
			$hasPermission = false;
			$permissions = explode(',', $item->permissions);
			foreach ($permissions as $permission) {
				if (WCF::getSession()->getPermission($permission)) {
					$hasPermission = true;
					break;
				}
			}
		}
		if (!$hasPermission) return false;
		
		return true;
	}
	
	/**
	 * Returns the list of menu items.
	 * 
	 * @return	array<wcf\data\user\profile\menu\item\UserProfileMenuItem>
	 */
	public function getMenuItems() {
		return $this->menuItems;
	}
	
	/**
	 * Returns the first menu item.
	 * 
	 * @return	wcf\data\user\profile\menu\item\UserProfileMenuItem
	 */
	public function getFirstMenuItem() {
		if (empty($this->menuItems)) {
			return null;
		}
		
		reset($this->menuItems);
		return current($this->menuItems);
	}
}
