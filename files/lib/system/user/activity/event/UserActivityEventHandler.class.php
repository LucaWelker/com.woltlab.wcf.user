<?php
namespace wcf\system\user\activity\event;
use wcf\data\user\activity\event\ViewableUserActivityEventList;
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
	 * Returns an object type by id.
	 * 
	 * @param	integer				$objectTypeID
	 * @return	wcf\data\object\type\ObjectType
	 */
	public function getObjectType($objectTypeID) {
		if (isset($this->objectTypes[$objectTypeID])) {
			return $this->objectTypes[$objectTypeID];
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
}
