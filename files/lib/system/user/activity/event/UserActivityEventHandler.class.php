<?php
namespace wcf\system\user\activity\event;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\user\activity\event\UserActivityEventAction;
use wcf\data\user\activity\event\ViewableUserActivityEventList;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

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
			$this->objectTypes['names'][$objectType->objectType] = $objectType->objectTypeID;
			$this->objectTypes['objects'][$objectType->objectTypeID] = $objectType;
		}
	}
	
	/**
	 * Returns an object type by id.
	 * 
	 * @param	integer				$objectTypeID
	 * @return	wcf\data\object\type\ObjectType
	 */
	public function getObjectType($objectTypeID) {
		if (isset($this->objectTypes['objects'][$objectTypeID])) {
			return $this->objectTypes['objects'][$objectTypeID];
		}
		
		return null;
	}
	
	/**
	 * Returns an object type id by object type name.
	 * 
	 * @param	string		$objectType
	 * @return	integer
	 */
	public function getObjectTypeID($objectType) {
		if (isset($this->objectTypes['names'][$objectType])) {
			return $this->objectTypes['names'][$objectType];
		}
		
		return null;
	}
	
	/**
	 * Counts available events.
	 * 
	 * @param	array		$userIDs
	 * @return	integer
	 */
	public function countEvents(array $userIDs) {
		$eventList = new ViewableUserActivityEventList($userIDs);
		return $eventList->countObjects();
	}
	
	/**
	 * Returns a list of events.
	 * 
	 * @param	array		$userIDs
	 * @param	integer		$sqlLimit
	 * @param	integer		$sqlOffset
	 * @param	string		$sqlOrderBy
	 * @return	wcf\data\user\activity\event\ViewableUserActivityEventList
	 */
	public function getEvents(array $userIDs, $sqlLimit = 20, $sqlOffset = 0, $sqlOrderBy = 'user_activity_event.time DESC') {
		$eventList = new ViewableUserActivityEventList($userIDs);
		$eventList->sqlLimit = $sqlLimit;
		$eventList->sqlOffset = $sqlOffset;
		$eventList->sqlOrderBy = $sqlOrderBy;
		$eventList->readObjects();
		
		return $eventList;
	}
	
	/**
	 * Fires a new activity event.
	 * 
	 * @param	string		$objectType
	 * @param	integer		$packageID
	 * @param	integer		$objectID
	 * @param	integer		$userID
	 * @param	integer		$time
	 * @param	array		$additonalData
	 * @return	wcf\data\user\activity\event\UserActivityEvent
	 */
	public function fireEvent($objectType, $packageID, $objectID, $userID = null, $time = TIME_NOW, $additonalData = array()) {
		$objectTypeID = $this->getObjectTypeID($objectType);
		if ($userID === null) $userID = WCF::getUser()->userID;
		
		$eventAction = new UserActivityEventAction(array(), 'create', array(
			'data' => array(
				'objectTypeID' => $objectTypeID,
				'packageID' => $packageID,
				'objectID' => $objectID,
				'userID' => $userID,
				'time' => $time,
				'additionalData' => serialize($additonalData)
			)
		));
		$returnValues = $eventAction->executeAction();
		
		return $returnValues['returnValues'];
	}
}
