<?php
namespace wcf\system\cache\builder;
use wcf\data\user\profile\menu\item\UserProfileMenuItem;
use wcf\system\WCF;

/**
 * Caches the user profile menu items.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
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
		$data = array();
		
		$sql = "SELECT		menuItemID, menuItem, permissions, options, packageDir, menu_item.packageID, className
			FROM		wcf".WCF_N."_user_profile_menu_item menu_item
			LEFT JOIN	wcf".WCF_N."_package package
			ON		(package.packageID = menu_item.packageID)
			ORDER BY	showOrder ASC";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		while ($row = $statement->fetchArray()) {
			$data[] = new UserProfileMenuItem(null, $row);
		}
				
		return $data;
	}
}
