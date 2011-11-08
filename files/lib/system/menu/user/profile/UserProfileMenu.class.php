<?php
namespace wcf\system\menu\user\profile;
use wcf\system\menu\TreeMenu;
use wcf\system\cache\CacheHandler;

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
class UserProfileMenu extends TreeMenu {
	/**
	 * @see	wcf\system\menu\TreeMenu::loadCache()
	 */
	protected function loadCache() {
		parent::loadCache();
		
		$cacheName = 'userProfileMenu-'.PACKAGE_ID;
		CacheHandler::getInstance()->addResource(
			$cacheName,
			WCF_DIR.'cache/cache.'.$cacheName.'.php',
			'wcf\system\cache\builder\UserProfileMenuCacheBuilder'
		);
		$this->menuItems = CacheHandler::getInstance()->get($cacheName);
	}
}
