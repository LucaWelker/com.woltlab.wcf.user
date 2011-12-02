<?php
namespace wcf\system\user\activity\event;
use wcf\data\user\activity\event\UserActivityEventList;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\SingletonFactory;

/**
 * User activity event handler.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.activity.event
 * @category 	Community Framework
 */
class UserActivityEventHandler extends SingletonFactory {
	/**
	 * cached object types
	 * @var	array<wcf\data\object\type\ObjectType>
	 */
	protected $objectTypes = array();
	
	/**
	 * @see	wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		// load object types
		$cache = ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.user.recentActivityEvent');
		foreach ($cache as $objectType) {
			$this->objectTypes[$objectType->objectTypeID] = $objectType;
		}
	}
	
	/**
	 * Counts available events.
	 * 
	 * @param	array		$userIDs
	 * @return	integer
	 */
	public function countEvents(array $userIDs) {
		$eventList = new UserActivityEventList();
		$eventList->getConditionBuilder()->add("user_activity_event.userID IN (?)", array($userIDs));
		$eventList->getConditionBuilder()->add("user_activity_event.packageID IN (?)", array(PackageDependencyHandler::getDependencies()));
		
		return $eventList->countObjects();
	}
	
	/**
	 * Returns a list of events.
	 * 
	 * @param	array		$userIDs
	 * @param	integer		$sqlLimit
	 * @param	integer		$sqlOffset
	 * @param	string		$sqlOrderBy
	 * @return	array<string>
	 */
	public function getEvents(array $userIDs, $sqlLimit = 20, $sqlOffset = 0, $sqlOrderBy = 'user_activity_event.time DESC') {
		$eventList = new UserActivityEventList();
		$eventList->getConditionBuilder()->add("user_activity_event.userID IN (?)", array($userIDs));
		$eventList->getConditionBuilder()->add("user_activity_event.packageID IN (?)", array(PackageDependencyHandler::getDependencies()));
		$eventList->sqlLimit = $sqlLimit;
		$eventList->sqlOffset = $sqlOffset;
		$eventList->sqlOrderBy = $sqlOrderBy;
		$eventList->readObjects();
		
		return $this->getEventOutput($eventList);
	}
	
	/**
	 * Returns a list of event outputs.
	 * 
	 * @param	wcf\data\user\activity\event\UserActivityEventList	$eventList
	 * @return	array<string>
	 */
	protected function getEventOutput(UserActivityEventList $eventList) {
		$events = array();
		$orderedList = array();
		foreach ($eventList as $event) {
			if (!isset($events[$event->objectTypeID])) {
				$objectType = $this->objectTypes[$event->objectTypeID];
				
				$events[$event->objectTypeID] = array(
					'className' => $objectType->className,
					'objects' => array()
				);
			}
			
			$events[$event->objectTypeID]['objects'][] = $event;
			$orderedList[] = $event->eventID;
		}
		
		// build event output
		$outputData = array();
		foreach ($events as $objectTypeID => $eventData) {
			$eventClass = call_user_func(array($eventData['className'], 'getInstance'));
			$eventClass->setEventData($eventData['objects']);
			
			foreach ($eventData['objects'] as $event) {
				$outputData[$event->eventID] = $eventClass->getOutput($event->eventID);
			}
		}
		
		// order events again
		$output = array();
		foreach ($orderedList as $eventID) {
			$output[] = $outputData[$eventID];
		}
		
		return $output;
	}
}
