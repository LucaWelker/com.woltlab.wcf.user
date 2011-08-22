<?php
namespace wcf\system\user\notification\object\type;
use wcf\data\user\friend\request\UserFriendRequest;
use wcf\data\user\friend\request\UserFriendRequestList;
use wcf\system\user\notification\object\type\AbstractUserNotificationObjectType;
use wcf\system\user\notification\object\UserFriendRequestUserNotificationObject;

/**
 * Represents a user friend request as a notification object type.
 *
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.notification.object.type
 * @category 	Community Framework
 */
class UserFriendRequestUserNotificationObjectType extends AbstractUserNotificationObjectType {
	/**
	 * @see wcf\system\user\notification\object\type\IUserNotificationObjectType::getObjectByID()
	 */
	public function getObjectByID($objectID) {
		$request = new UserFriendRequest($objectID);
		if (!$request->requestID) {
			// create empty object for unknown request id
			$request = new UserFriendRequest(null, array('requestID' => $objectID));
		}
		
		return array($request->requestID => new UserFriendRequestUserNotificationObject($request));
	}

	/**
	 * @see wcf\system\user\notification\object\type\IUserNotificationObjectType::getObjectsByIDs()
	 */
	public function getObjectsByIDs(array $objectIDs) {
		$requestList = new UserFriendRequestList();
		$requestList->getConditionBuilder()->add('user_friend_request.requestID IN (?)', array($objectIDs));
		$requestList->readObjects();
		$requests = array();
		
		foreach ($requestList->getObjects() as $request) {
			$requests[$request->requestID] = new UserFriendRequestUserNotificationObject($request);
		}
		
		foreach ($objectIDs as $objectID) {
			// append empty objects for unknown ids
			if (!isset($requests[$objectID])) {
				$requests[$objectID] = new UserFriendRequestUserNotificationObjectType(new UserFriendRequest(null, array('requestID' => $objectID)));
			}
		}
		
		return $requests;
	}
}
