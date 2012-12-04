<?php
namespace wcf\system\cache\builder;
use wcf\data\user\profile\menu\item\UserProfileMenuItem;
use wcf\system\database\util\PreparedStatementConditionBuilder;
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
		
		// get all menu items and filter menu items with low priority
		$sql = "SELECT		menuItem, menuItemID 
			FROM		wcf".WCF_N."_user_profile_menu";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		$itemIDs = array();
		while ($row = $statement->fetchArray()) {
			$itemIDs[$row['menuItem']] = $row['menuItemID'];
		}
		
		if (!empty($itemIDs)) {
			// get needed menu items
			$conditions = new PreparedStatementConditionBuilder();
			$conditions->add("menu_item.menuItemID IN (?)", array($itemIDs));
			
			$sql = "SELECT		menuItemID, menuItem, permissions, options, packageDir, className,
						CASE WHEN parentPackageID <> 0 THEN parentPackageID ELSE menu_item.packageID END AS packageID
				FROM		wcf".WCF_N."_user_profile_menu_item menu_item
				LEFT JOIN	wcf".WCF_N."_package package
				ON		(package.packageID = menu_item.packageID)
				".$conditions."
				ORDER BY	showOrder ASC";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($conditions->getParameters());
			while ($row = $statement->fetchArray()) {
				$data[] = new UserProfileMenuItem(null, $row);
			}
		}
		
		return $data;
	}
}
