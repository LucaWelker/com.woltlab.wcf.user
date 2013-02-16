<?php
namespace wcf\system\event\listener;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\event\IEventListener;
use wcf\system\visitTracker\VisitTracker;
use wcf\system\WCF;

/**
 * Extends the daily system cleanup.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.event.listener
 * @category	Community Framework
 */
class UserCleanUpListener implements IEventListener {
	/**
	 * @see	\wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		// clean up notifications
		$sql = "DELETE FROM	wcf".WCF_N."_user_notification
			WHERE		time < ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
			(TIME_NOW - 86400 * USER_CLEANUP_NOTIFICATION_LIFETIME)
		));
		
		// clean up user activity events
		$sql = "DELETE FROM	wcf".WCF_N."_user_activity_event
			WHERE		time < ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
			(TIME_NOW - 86400 * USER_CLEANUP_ACTIVITY_EVENT_LIFETIME)
		));
		
		// clean up profile visitors
		$sql = "DELETE FROM	wcf".WCF_N."_user_profile_visitor
			WHERE		time < ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
			(TIME_NOW - 86400 * USER_CLEANUP_PROFILE_VISITOR_LIFETIME)
		));
		
		// tracked visits
		$sql = "DELETE FROM	wcf".WCF_N."_tracked_visit
			WHERE		objectTypeID = ?
					AND visitTime < ?";
		$statement1 = WCF::getDB()->prepareStatement($sql);
		$sql = "DELETE FROM	wcf".WCF_N."_tracked_visit_type
			WHERE		objectTypeID = ?
					AND visitTime < ?";
		$statement2 = WCF::getDB()->prepareStatement($sql);
		foreach (ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.visitTracker.objectType') as $objectType) {
			// get lifetime
			$lifetime = ($objectType->lifetime ?: VisitTracker::DEFAULT_LIFETIME);
			
			// delete data
			$statement1->execute(array(
				$objectType->objectTypeID,
				$lifetime
			));
			$statement2->execute(array(
				$objectType->objectTypeID,
				$lifetime
			));
		}
	}
}
