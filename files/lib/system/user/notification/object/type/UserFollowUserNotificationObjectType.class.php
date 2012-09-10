<?php
namespace wcf\system\user\notification\object\type;
use wcf\data\object\type\AbstractObjectTypeProcessor;
use wcf\data\user\follow\UserFollow;
use wcf\data\user\follow\UserFollowList;
use wcf\system\user\notification\object\UserFollowUserNotificationObject;

/**
 * Represents a following user as a notification object type.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.notification.object.type
 * @category 	Community Framework
 */
class UserFollowUserNotificationObjectType extends AbstractObjectTypeProcessor implements IUserNotificationObjectType {
	/**
	 * @see	wcf\system\user\notification\object\type\IUserNotificationObjectType::getObjectByID()
	 */
	public function getObjectByID($objectID) {
		$follow = new UserFollow($objectID);
		if (!$follow->followID) {
			// create empty object for unknown follow id
			$follow = new UserFollow(null, array('followID' => $objectID));
		}
		
		return array($follow->followID => new UserFollowUserNotificationObject($follow));
	}
	
	/**
	 * @see	wcf\system\user\notification\object\type\IUserNotificationObjectType::getObjectsByIDs()
	 */
	public function getObjectsByIDs(array $objectIDs) {
		$followList = new UserFollowList();
		$followList->getConditionBuilder()->add("user_follow.followID IN (?)", array($objectIDs));
		$followList->readObjects();
		
		$follows = array();
		foreach ($followList as $follow) {
			$follows[$follow->followID] = new UserFollowUserNotificationObject($follow);
		}
		
		foreach ($objectIDs as $objectID) {
			// append empty objects for unknown ids
			if (!isset($follows[$objectID])) {
				$follows[$objectID] = new UserFollowUserNotificationObject(new UserFollow(null, array('followID' => $objectID)));
			}
		}
		
		return $follows;
	}
}
