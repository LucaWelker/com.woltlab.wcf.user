<?php
namespace wcf\data\user\profile\menu\item;
use wcf\data\DatabaseObjectList;

/**
 * Represents a list of user profile menu items.
 * 
 * @author 	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.profile.menu.item
 * @category 	Community Framework
 */
class UserProfileMenuItemList extends DatabaseObjectList {
	/**
	 * @see	wcf\data\DatabaseObjectList::$className
	 */
	public $className = 'wcf\data\user\profile\menu\item\UserProfileMenuItem';
}
