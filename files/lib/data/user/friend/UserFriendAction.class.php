<?php
namespace wcf\data\user\friend;
use wcf\data\AbstractDatabaseObjectAction;

/**
 * Executes friend-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.friend
 * @category 	Community Framework
 */
class UserFriendAction extends AbstractDatabaseObjectAction {
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$className
	 */
	public $className = 'wcf\data\user\friend\UserFriendEditor';
}
