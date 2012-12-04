<?php
namespace wcf\system\menu\user;
use wcf\system\cache\CacheHandler;
use wcf\system\menu\ITreeMenuItem;
use wcf\system\menu\TreeMenu;

/**
 * Builds the user menu.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.menu.user
 * @category	Community Framework
 */
class UserMenu extends TreeMenu {
	/**
	 * @see	wcf\system\menu\TreeMenu::loadCache()
	 */
	protected function loadCache() {
		parent::loadCache();
		
		CacheHandler::getInstance()->addResource(
			'userMenu',
			WCF_DIR.'cache/cache.userMenu.php',
			'wcf\system\cache\builder\UserMenuCacheBuilder'
		);
		$this->menuItems = CacheHandler::getInstance()->get('userMenu');
	}
	
	/**
	 * @see	wcf\system\menu\TreeMenu::checkMenuItem()
	 */
	protected function checkMenuItem(ITreeMenuItem $item) {
		if (!parent::checkMenuItem($item)) return false;
		
		return $item->getProcessor()->isVisible();
	}
}
