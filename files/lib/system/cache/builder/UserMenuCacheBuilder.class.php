<?php
namespace wcf\system\cache\builder;
use wcf\data\user\menu\item\UserMenuItem;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;

/**
 * Caches the user menu items tree.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.cache.builder
 * @category	Community Framework
 */
class UserMenuCacheBuilder implements ICacheBuilder {
	/**
	 * @see	wcf\system\cache\ICacheBuilder::getData()
	 */
	public function getData(array $cacheResource) { 
		$data = array();
		
		// get all option categories and filter categories with low priority
		$sql = "SELECT		categoryName, categoryID
			FROM		wcf".WCF_N."_user_option_category";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		$categoryIDs = array();
		while ($row = $statement->fetchArray()) {
			$categoryIDs[$row['categoryName']] = $row['categoryID'];
		}
		
		if (!empty($categoryIDs)) {
			if (!isset($data['wcf.user.menu.settings'])) {
				$data['wcf.user.menu.settings'] = array();
			}
			
			$conditions = new PreparedStatementConditionBuilder();
			$conditions->add("categoryID IN (?)", array($categoryIDs));
			$conditions->add("parentCategoryName = 'settings'");
			
			// get needed option categories
			$sql = "SELECT		*
				FROM		wcf".WCF_N."_user_option_category
				".$conditions."
				ORDER BY	showOrder";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($conditions->getParameters());
			while ($row = $statement->fetchArray()) {
				$categoryShortName = str_replace('settings.', '', $row['categoryName']);
				
				$data['wcf.user.menu.settings'][] = new UserMenuItem(null, array(
					'packageID' => $row['packageID'],
					'menuItem' => 'wcf.user.option.category.'.$row['categoryName'],
					'parentMenuItem' => 'wcf.user.menu.settings',
					'menuItemLink' => 'index.php/Settings/'.($categoryShortName != 'general' ? '?category='.$categoryShortName : ''),
					'permissions' => $row['permissions'],
					'options' => $row['options']
				));
			}
		}
		
		// get all menu items and filter menu items with low priority
		$sql = "SELECT		menuItem, menuItemID
			FROM		wcf".WCF_N."_user_menu_item";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		$itemIDs = array();
		while ($row = $statement->fetchArray()) {
			$itemIDs[$row['menuItem']] = $row['menuItemID'];
		}
		
		if (!empty($itemIDs)) {
			$conditions = new PreparedStatementConditionBuilder();
			$conditions->add("menuItemID IN (?)", array($itemIDs));
			
			// get needed menu items and build item tree
			$sql = "SELECT		packageID, menuItem, parentMenuItem,
						menuItemLink, permissions, options, className
				FROM		wcf".WCF_N."_user_menu_item
				".$conditions."
				ORDER BY	showOrder ASC";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($conditions->getParameters());
			while ($row = $statement->fetchArray()) {
				if (!isset($data[$row['parentMenuItem']])) {
					$data[$row['parentMenuItem']] = array();
				}
				
				$data[$row['parentMenuItem']][] = new UserMenuItem(null, $row);
			}
		}
		
		return $data;
	}
}
