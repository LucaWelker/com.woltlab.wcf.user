<?php
namespace wcf\system\user\activity\point;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\user\activity\event\UserActivityEvent;
use wcf\data\user\activity\point\event\UserActivityPointEventAction;
use wcf\data\user\activity\point\event\UserActivityPointEventEditor;
use wcf\data\user\activity\point\event\UserActivityPointEventList;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\exception\SystemException;
use wcf\system\user\activity\event\UserActivityEventHandler;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * Handles the user activity point events
 * 
 * @author	Tim Düsterhus, Matthias Schmidt
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.activity.point
 * @category 	Community Framework
 */
class UserActivityPointHandler extends SingletonFactory {
	/**
	 * list of user activity point object types
	 * @var	array<wcf\data\object\type\ObjectType>
	 */
	protected $objectTypes = array();
	
	/**
	 * maps the user activity point object type ids to their object type names
	 * @var	array<string>
	 */
	protected $objectTypeNames = array();
	
	/**
	 * lists of the ids of user activity point event object type grouped by
	 * the id of the user activity event object type name they belong to
	 * @var	array<array>
	 */
	protected $userActivityEventObjectTypeIDs = array();
	
	/**
	 * Adds a new user activity point event.
	 * 
	 * @param	string			$objectType
	 * @param	integer			$objectID
	 * @param	integer			$userID
	 * @param	array<mixed>		$additionalData
	 * @param	boolean			$skipCacheUpdate	should the cache update be skipped (you have to call updateCaches manually afterwards!)
	 */
	public function fireEvent($objectType, $objectID, $userID = null, array $additionalData = array(), $skipCacheUpdate = false) {
		$_objectType = $this->getObjectTypeByName($objectType);
		if ($_objectType === null) {
			throw new SystemException("Unknown user activity point object type '".$objectType."'");
		}
		
		if ($userID === null) $userID = WCF::getUser()->userID;
		if (!$userID) throw new SystemException("Cannot fire user activity point events for guests");
		
		$eventAction = new UserActivityPointEventAction(array(), 'create', array(
			'data' => array(
				'objectTypeID' => $_objectType->objectTypeID,
				'objectID' => $objectID,
				'userID' => $userID,
				'additionalData' => serialize($additionalData)
			)
		));
		$returnValues = $eventAction->executeAction();
		
		if (!$skipCacheUpdate) $this->updateCaches(array($userID));
		
		return $returnValues['returnValues'];
	}
	
	/**
	 * Fires a new user activity point event by the given user activity event.
	 * 
	 * @param	wcf\data\user\activity\event\UserActivityEvent	$userActivityEvent
	 */
	public function fireUserActivityEvent(UserActivityEvent $userActivityEvent) {
		$userActivityEventObjectType = UserActivityEventHandler::getInstance()->getObjectType($userActivityEvent->objectTypeID);
		$objectTypes = $this->getObjectTypesByUserActivityEvent($userActivityEventObjectType->objectType);
		$userIDs = array();
		
		foreach ($objectTypes as $objectType) {
			$userIDs[] = $userActivityEvent->userID;
			$this->fireEvent($objectType, $userActivityEvent->objectID, $userActivityEvent->userID, $userActivityEvent->additionalData, true);
		}
		$this->updateCaches($userIDs);
	}
	
	/**
	 * Removes events for objects that no longer exist.
	 * 
	 * @param	string		$objectType
	 * @param	array<integer>	$objectIDs
	 */
	public function removeEvents($objectType, array $objectIDs) {
		if (empty($objectIDs)) return;
		
		// get and validate object type
		$_objectType = $this->getObjectTypeByName($objectType);
		if ($_objectType === null) {
			throw new SystemException("Unknown user activity point object type '".$objectType."'");
		}
		
		// read deleted events
		$eventList = new UserActivityPointEventList();
		$eventList->sqlLimit = 0;
		$eventList->getConditionBuilder()->add("objectType = ?", array($objectType));
		$eventList->getConditionBuilder()->add("objectID IN (?)", array($objectIDs));
		$eventList->readObjects();
		
		// delete events
		UserActivityPointEventEditor::deleteAll($eventList->getObjectIDs());
		
		$userIDs = array();
		foreach ($eventList as $event) $userIDs[] = $event->userID;
		$this->updateCaches($userIDs);
	}
	
	/**
	 * Returns the user activity point event object type with the given id or
	 * null if no such object tyoe exists.
	 * 
	 * @param	integer		$objectTypeID
	 * @return	wcf\data\object\type\ObjectType
	 */
	public function getObjectType($objectTypeID) {
		if (isset($this->objectTypeNames[$objectTypeID])) {
			return $this->getObjectTypeByName($this->objectTypeNames[$objectTypeID]);
		}
		
		return null;
	}
	
	/**
	 * Returns the user activity point event object type with the given name
	 * or null if no such object type exists.
	 * 
	 * @param	string		$objectType
	 * @return	wcf\data\object\type\ObjectType
	 */
	public function getObjectTypeByName($objectType) {
		if (isset($this->objectTypes[$objectType])) {
			return $this->objectTypes[$objectType];
		}
		
		return null;
	}
	
	/**
	 * Returns the user activity point event object types for the given user
	 * activity event object type.
	 * 
	 * @param	string		$eventObjectType
	 * @return	array<wcf\data\object\type\ObjectType>
	 */
	public function getObjectTypesByUserActivityEvent($eventObjectType) {
		$objectTypes = array();
		if (isset($this->userActivityEventObjectTypeIDs[$eventObjectType])) {
			foreach ($this->userActivityEventObjectTypeIDs[$eventObjectType] as $objectTypeID) {
				$objectTypes[] = $this->getObjectType($objectTypeID);
			}
		}
		
		return $objectTypes;
	}
	
	/**
	 * @see	wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		$this->objectTypes = ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.user.activityPointEvent');
		
		foreach ($this->objectTypes as $objectType) {
			$this->objectTypeNames[$objectType->objectTypeID] = $objectType->objectType;
			if ($objectType->useractivityevent) {
				if (!isset($this->userActivityEventObjectTypeIDs[$objectType->useractivityevent])) {
					$this->userActivityEventObjectTypeIDs[$objectType->useractivityevent] = array();
				}
				
				$this->userActivityEventObjectTypeIDs[$objectType->useractivityevent][] = $objectType->objectTypeID;
			}
		}
	}
	
	/**
	 * Updates the caches for the given user. When no user is given the current user is used.
	 * 
	 * @param wcf\data\user\User $user
	 */
	public function updateCache(User $user = null) {
		if ($user === null) $user = WCF::getUser();
		
		$this->updateCaches(array($user->userID));
	}
	
	/**
	 * Updates the caches for the given users. When an empty array is given the caches are recalculated for
	 * EVERY SINGLE user in this installation.
	 * 
	 * @param array<integer> $userIDs
	 */
	public function updateCaches(array $userIDs) {
		$objectTypes = array();
		foreach ($this->objectTypes as $objectType) $objectTypes[$objectType->objectTypeID] = $objectType->points;
		
		$conditionBuilder = new PreparedStatementConditionBuilder();
		$conditionBuilder->add("objectTypeID IN (?)", array(array_keys($objectTypes)));
		if (!empty($userIDs)) $conditionBuilder->add("userID IN (?)", array($userIDs));
		
		WCF::getDB()->beginTransaction();
		// delete old data
		$sql = "DELETE FROM	wcf".WCF_N."_user_activity_points
			".$conditionBuilder;
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($conditionBuilder->getParameters());
		
		$conditionBuilder = new PreparedStatementConditionBuilder();
		if (!empty($userIDs)) $conditionBuilder->add("userID IN (?)", array($userIDs));
		else $conditionBuilder->add("1");
		
		// use INSERT … SELECT as this makes bulk updating easier
		$sql = "INSERT INTO 
				wcf".WCF_N."_user_activity_points (userID, objectTypeID, activityPoints)
				
				SELECT	userID, 
					objectTypeID, 
					(COUNT(*) * ?) AS activityPoints
				FROM
					wcf".WCF_N."_user_activity_point_event 
				".$conditionBuilder." AND objectTypeID = ? 
				GROUP BY
					userID, objectTypeID";
		$statement = WCF::getDB()->prepareStatement($sql);
		foreach ($objectTypes as $objectTypeID => $points) {
			$statement->execute(array_merge((array) $points, $conditionBuilder->getParameters(), (array) $objectTypeID));
		}
		
		// and reset general cache
		$sql = "UPDATE	wcf".WCF_N."_user user
			SET	user.activityPoints =
				COALESCE((
					SELECT	SUM(activityPoints) AS activityPoints 
					FROM	wcf".WCF_N."_user_activity_points points 
					WHERE	points.userID = user.userID 
					GROUP BY user.userID
				), 0)
			".str_replace('userID', 'user.userID', $conditionBuilder);
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($conditionBuilder->getParameters());
		
		WCF::getDB()->commitTransaction();
	}
}
