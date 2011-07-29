<?php
namespace wcf\data\user\friend;
use wcf\data\DatabaseObjectList;

/**
 * Represents a list of friends.
 * 
 * @author 	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.friend
 * @category 	Community Framework
 */
class UserFriendList extends DatabaseObjectList {
	/**
	 * @see	wcf\data\DatabaseObjectList::$className
	 */
	public $className = 'wcf\data\user\friend\UserFriend';
}
