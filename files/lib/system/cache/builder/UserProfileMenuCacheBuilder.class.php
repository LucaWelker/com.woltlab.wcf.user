<?php
namespace wcf\system\cache\builder;
use wcf\data\user\profile\menu\item\UserProfileMenuItemList;

/**
 * Caches the user profile menu items.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	system.cache.builder
 * @category	Community Framework
 */
class UserProfileMenuCacheBuilder implements ICacheBuilder {
	/**
	 * @see	wcf\system\cache\ICacheBuilder::getData()
	 */
	public function getData(array $cacheResource) { 
		$itemList = new UserProfileMenuItemList();
		$itemList->sqlLimit = 0;
		$itemList->sqlOrderBy = "user_profile_menu_item.showOrder ASC";
		$itemList->readObjects();
		
		return $itemList->getObjects();
	}
}
