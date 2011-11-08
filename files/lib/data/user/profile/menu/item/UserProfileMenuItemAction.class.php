<?php
namespace wcf\data\user\profile\menu\item;
use wcf\data\AbstractDatabaseObjectAction;

/**
 * Executes user profile menu item-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.profile.menu.item
 * @category 	Community Framework
 */
class UserProfileMenuItemAction extends AbstractDatabaseObjectAction {
	/**
	 * @see wcf\data\AbstractDatabaseObjectAction::$className
	 */
	protected $className = 'wcf\data\user\profile\menu\item\UserProfileMenuItemEditor';
}
