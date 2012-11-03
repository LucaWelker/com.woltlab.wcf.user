<?php
namespace wcf\data\user\notification;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\ValidateActionException;
use wcf\system\request\LinkHandler;
use wcf\system\user\notification\UserNotificationHandler;
use wcf\system\WCF;

/**
 * Executes user notification-related actions.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.notification
 * @subpackage	data.user.notification
 * @category	Community Framework
 */
class UserNotificationAction extends AbstractDatabaseObjectAction {
	/**
	 * Does nothing.
	 */	
	public function validateLoad() { }
	
	/**
	 * Loads user notifications.
	 * 
	 * @return	array<array>
	 */	
	public function load() {
		$returnValues = UserNotificationHandler::getInstance()->getNotifications();
		$returnValues['totalCount'] = UserNotificationHandler::getInstance()->getNotificationCount();
		
		// check if additional notifications are available
		if ($returnValues['count'] < $returnValues['totalCount']) {
			$returnValues['showAllLink'] = LinkHandler::getInstance()->getLink('NotificationList');
		}
		
		return $returnValues;
	}
	
	/**
	 * Validates if given notification id is valid for current user.
	 */
	public function validateMarkAsConfirmed() {
		// validate notification id
		if (!isset($this->parameters['notificationID'])) {
			throw new ValidateActionException("missing parameter 'notificationID'");
		}
		
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_user_notification_to_user
			WHERE	notificationID = ?
				AND userID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
			$this->parameters['notificationID'],
			WCF::getUser()->userID
		));
		$row = $statement->fetchArray();
		
		if (!$row['count']) {
			throw new ValidateActionException("notification id is invalid");
		}
	}
	
	/**
	 * Marks a notification as confirmed.
	 * 
	 * @return	array
	 */
	public function markAsConfirmed() {
		UserNotificationHandler::getInstance()->markAsConfirmed($this->parameters['notificationID']);
		
		return array(
			'notificationID' => $this->parameters['notificationID'],
			'totalCount' => UserNotificationHandler::getInstance()->getNotificationCount()
		);
	}
}
