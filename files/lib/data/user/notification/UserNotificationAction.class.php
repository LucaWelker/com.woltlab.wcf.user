<?php
namespace wcf\data\user\notification;
use wcf\data\user\notification\UserNotificationEditor;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\UserInputException;
use wcf\system\request\LinkHandler;
use wcf\system\user\notification\UserNotificationHandler;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\WCF;

/**
 * Executes user notification-related actions.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.notification
 * @category	Community Framework
 */
class UserNotificationAction extends AbstractDatabaseObjectAction {
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::create()
	 */
	public function create() {
		// create notification
		$notification = parent::create();
		
		// save recpients
		if (!empty($this->parameters['recipients'])) {
			$sql = "INSERT INTO	wcf".WCF_N."_user_notification_to_user
						(notificationID, userID, mailNotified)
				VALUES		(?, ?, ?)";
			$statement = WCF::getDB()->prepareStatement($sql);
			foreach ($this->parameters['recipients'] as $recipient) {
				$statement->execute(array($notification->notificationID, $recipient->userID, ($recipient->mailNotificationType == 'daily' ? 0 : 1)));
			}
		}
		
		return $notification;
	}
	
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
		$this->readInteger('notificationID');
		
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
		
		// pretend it was marked as confirmed
		if (!$row['count']) {
			$this->parameters['alreadyConfirmed'] = true;
		}
	}
	
	/**
	 * Marks a notification as confirmed.
	 * 
	 * @return	array
	 */
	public function markAsConfirmed() {
		if (!isset($this->parameters['alreadyConfirmed'])) {
			$sql = "DELETE FROM	wcf".WCF_N."_user_notification_to_user
				WHERE		notificationID = ?
						AND userID = ?";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array(
				$this->parameters['notificationID'],
				WCF::getUser()->userID
			));
			
			// remove entirely read notifications
			$sql = "SELECT	COUNT(*) as count
				FROM	wcf".WCF_N."_user_notification_to_user
				WHERE	notificationID = ?";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array($this->parameters['notificationID']));
			$row = $statement->fetchArray();
			if (!$row['count']) {
				UserNotificationEditor::deleteAll(array($this->parameters['notificationID']));
			}
			
			// reset notification count
			UserStorageHandler::getInstance()->reset(array(WCF::getUser()->userID), 'userNotificationCount');
		}
		
		return array(
			'notificationID' => $this->parameters['notificationID'],
			'totalCount' => UserNotificationHandler::getInstance()->getNotificationCount()
		);
	}
}
