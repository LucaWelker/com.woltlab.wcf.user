<?php
namespace wcf\system\event\listener;
use wcf\system\cache\builder\UserNotificationEventCacheBuilder;
use wcf\system\event\IEventListener;
use wcf\system\WCF;

/**
 * Handles preset notifications.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.event.listener
 * @category	Community Framework
 */
class UserActionPresetNotificationListener implements IEventListener {
	/**
	 * @see	\wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if ($eventObj->getActionName() != 'create') return;
		
		$returnValues = $eventObj->getReturnValues();
		$user = $returnValues['returnValues'];
		
		$sql = "INSERT INTO	wcf".WCF_N."_user_notification_event_to_user
					(userID, eventID)
			VALUES		(?, ?)";
		$statement = WCF::getDB()->prepareStatement($sql);
		foreach (UserNotificationEventCacheBuilder::getInstance()->getData() as $events) {
			foreach ($events as $event) {
				if ($event->preset) {
					$statement->execute(array($user->userID, $event->eventID));
				}
			} 
		}
	}
}
