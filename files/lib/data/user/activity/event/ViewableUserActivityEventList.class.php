<?php
namespace wcf\data\user\activity\event;
use wcf\data\user\UserProfile;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\user\activity\event\UserActivityEventHandler;

/**
 * Represents a list of viewable user activity events.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.activity.event
 * @category 	Community Framework
 */
class ViewableUserActivityEventList extends UserActivityEventList {
	/**
	 * @see	wcf\data\DatabaseObjectList::$sqlLimit
	 */
	public $sqlLimit = 20;
	
	/**
	 * @see	wcf\data\DatabaseObjectList::$sqlOrderBy
	 */
	public $sqlOrderBy = 'user_activity_event.time DESC';
	
	/**
	 * @see	wcf\data\DatabaseObjectList::__construct()
	 */
	public function __construct() {
		parent::__construct();
		
		$this->getConditionBuilder()->add("user_activity_event.packageID IN (?)", array(PackageDependencyHandler::getInstance()->getDependencies()));
	}
	
	/**
	 * @see	wcf\data\DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		parent::readObjects();
		
		$userIDs = array();
		$eventGroups = array();
		foreach ($this->objects as &$event) {
			$userIDs[] = $event->userID;
			$event = new ViewableUserActivityEvent($event);
			
			if (!isset($eventGroups[$event->objectTypeID])) {
				$objectType = UserActivityEventHandler::getInstance()->getObjectType($event->objectTypeID);
				$eventGroups[$event->objectTypeID] = array(
					'className' => $objectType->className,
					'objects' => array()
				);
			}
			
			$eventGroups[$event->objectTypeID]['objects'][] = $event;
		}
		unset($event);
		
		// set user profiles
		if (!empty($userIDs)) {
			$userIDs = array_unique($userIDs);
			
			$users = UserProfile::getUserProfiles($userIDs);
			foreach ($this->objects as $event) {
				$event->setUserProfile($users[$event->userID]);
			}
		}
		
		// parse events
		foreach ($eventGroups as $eventData) {
			$eventClass = call_user_func(array($eventData['className'], 'getInstance'));
			$eventClass->prepare($eventData['objects']);
		}
	}
}
