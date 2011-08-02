<?php
namespace wcf\data\user\friend;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 * Represents a user's friend.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.friend
 * @category 	Community Framework
 */
class UserFriend extends DatabaseObject {
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'user_friend';
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableIndexName
	 */
	protected static $databaseTableIndexName = 'friendID';
	
	/**
	 * Gets friend object by given user id and friend user id. 
	 * 
	 * @param	integer		$userID
	 * @param	integer		$friendUserID
	 * @return	wcf\data\user\friend\UserFriend
	 */
	public static function getFriend($userID, $friendUserID) {
		$sql = "SELECT	*
			FROM	wcf".WCF_N."_user_friend
			WHERE	userID = ?
				AND friendUserID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array($userID, $friendUserID));
		$row = $statement->fetchArray();
		if ($row !== false) return new UserFriend(null, $row); 
		
		return null;
	}
}
