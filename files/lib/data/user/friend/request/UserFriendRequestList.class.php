<?php
namespace wcf\data\user\friend\request;
use wcf\data\DatabaseObjectList;

/**
 * Represents a list of friend requests.
 * 
 * @author 	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.friend.request
 * @category 	Community Framework
 */
class UserFriendRequestList extends DatabaseObjectList {
	/**
	 * @see	wcf\data\DatabaseObjectList::$className
	 */
	public $className = 'wcf\data\user\friend\request\UserFriendRequest';
}
