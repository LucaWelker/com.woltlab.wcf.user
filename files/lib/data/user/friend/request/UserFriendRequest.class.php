<?php
namespace wcf\data\user\friend\request;
use wcf\data\DatabaseObject;

/**
 * Represents a friend request.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.friend.request
 * @category 	Community Framework
 */
class UserFriendRequest extends DatabaseObject {
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'user_friend_request';
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableIndexName
	 */
	protected static $databaseTableIndexName = 'requestID';
	
	/**
	 * Gets request object by given user id and friend user id. 
	 * 
	 * @param	integer		$userID
	 * @param	integer		$friendUserID
	 * @return	wcf\data\user\friend\UserFriend
	 */
	public static function getRequest($userID, $friendUserID) {
		$sql = "SELECT	*
			FROM	wcf".WCF_N."_user_friend_request
			WHERE	userID = ?
				AND friendID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array($userID, $friendUserID));
		$row = $statement->fetchArray();
		if ($row !== false) return new UserFriendRequest(null, $row); 
		
		return null;
	}
}
