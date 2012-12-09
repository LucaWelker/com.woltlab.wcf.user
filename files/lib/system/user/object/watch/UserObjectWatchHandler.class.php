<?php
namespace wcf\system\user\object\watch;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\user\notification\object\IUserNotificationObject;
use wcf\system\user\notification\UserNotificationHandler;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * Handles watched objects.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.object.watch
 * @category	Community Framework
 */
class UserObjectWatchHandler extends SingletonFactory {
	/**
	 * object type id cache
	 * @var	array<integer>
	 */
	protected $objectTypeIDs = array();
	
	/**
	 * number of unread watched objects
	 * @var	array<integer>
	 */
	protected $unreadObjectCount = array();
	
	/**
	 * Gets the object type ids of the watched objects for given user.
	 * 
	 * @param	integer		$userID
	 * @return	array<integer>
	 */
	public function getObjectTypeIDs($userID = null) {
		if ($userID === null) $userID = WCF::getUser()->userID;
		
		if (!isset($this->objectTypeIDs[$userID])) {
			$this->objectTypeIDs[$userID] = 0;
			
			// load storage data
			UserStorageHandler::getInstance()->loadStorage(array($userID));
				
			// get ids
			$data = UserStorageHandler::getInstance()->getStorage(array($userID), 'userObjectWatchTypeIDs');
			
			// cache does not exist or is outdated
			if ($data[$userID] === null) {
				$this->objectTypeIDs[$userID] = $objectTypeIDs = array();
				foreach (ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.user.objectWatch') as $objectType) {
					$objectTypeIDs[] = $objectType->objectTypeID;
				}
				
				if (!empty($objectTypeIDs)) {
					$conditionBuilder = new PreparedStatementConditionBuilder();
					$conditionBuilder->add("objectTypeID IN (?)", array($objectTypeIDs));
					$conditionBuilder->add("userID = ?", array($userID));
					
					$sql = "SELECT	DISTINCT objectTypeID
						FROM	wcf".WCF_N."_user_object_watch
						".$conditionBuilder->__toString();
					$statement = WCF::getDB()->prepareStatement($sql);
					$statement->execute($conditionBuilder->getParameters());
					while ($row = $statement->fetchArray()) {
						$this->objectTypeIDs[$userID][] = $row['objectTypeID'];
					}
				}
				
				// update storage data
				UserStorageHandler::getInstance()->update($userID, 'userObjectWatchTypeIDs', serialize($this->objectTypeIDs[$userID]));
			}
			else {
				$this->objectTypeIDs[$userID] = unserialize($data[$userID]);
			}
		}
		
		return $this->objectTypeIDs[$userID];
	}
	
	/**
	 * Returns the number of unread watched objects for given user.
	 * 
	 * @param	integer		$userID
	 * @return	integer
	 */
	public function getUnreadObjectCount($userID = null) {
		if ($userID === null) $userID = WCF::getUser()->userID;
		
		if (!isset($this->unreadObjectCount[$userID])) {
			$this->unreadObjectCount[$userID] = 0;
			
			// load storage data
			UserStorageHandler::getInstance()->loadStorage(array($userID));
				
			// get ids
			$data = UserStorageHandler::getInstance()->getStorage(array($userID), 'unreadUserObjectWatchCount');
			
			// cache does not exist or is outdated
			if ($data[$userID] === null) {
				$this->unreadObjectCount[$userID] = 0;
				
				// get type ids
				$objectTypeIDs = $this->getObjectTypeIDs($userID);
				
				if (!empty($objectTypeIDs)) {
					$sql = '';
					$parameters = array();
					
					foreach ($objectTypeIDs as $objectTypeID) {
						$processor = ObjectTypeCache::getInstance()->getObjectType($objectTypeID)->getProcessor();
						if (($data = $processor->getUnreadCount($userID)) !== null) {
							if (!empty($sql)) $sql .= ' + ';
							$sql .= '('.$data['sql'].')';
							
							$parameters = array_merge($parameters, $data['parameters']);
						}
					}
					
					if (!empty($sql)) {
						$statement = WCF::getDB()->prepareStatement('SELECT '.$sql.' AS count');
						$statement->execute($parameters);
						$row = $statement->fetchArray();
						$this->unreadObjectCount[$userID] = $row['count'];
					}
				}
				
				// update storage data
				UserStorageHandler::getInstance()->update($userID, 'unreadUserObjectWatchCount', serialize($this->unreadObjectCount[$userID]));
			}
			else {
				$this->unreadObjectCount[$userID] = unserialize($data[$userID]);
			}
		}
		
		return $this->unreadObjectCount[$userID];
	}
	
	/**
	 * Returns a list of unread objects.
	 * 
	 * @param	integer				$userID
	 * @param	integer				$limit
	 * @return	array<wcf\data\IWatchedObject>
	 */
	public function getUnreadObjects($userID = null, $limit = 5) {
		if ($userID === null) $userID = WCF::getUser()->userID;
		$objects = array();
		
		// get type ids
		$objectTypeIDs = $this->getObjectTypeIDs($userID);
		if (!empty($objectTypeIDs)) {
			foreach ($objectTypeIDs as $objectTypeID) {
				$processor = ObjectTypeCache::getInstance()->getObjectType($objectTypeID)->getProcessor();
				$objects = array_merge($objects, $processor->getUnreadObjects($userID, $limit));
			}
			
			// sort by last update time (latest first)
			usort($objects, function($a, $b) {
				if ($a->getLastUpdateTime() == $b->getLastUpdateTime()) {
					return 0;
				}
				
				return ($a->getLastUpdateTime() < $b->getLastUpdateTime()) ? 1 : -1;
			});
			
			$length = count($objects);
			while ($length > $limit) {
				$length--;
				unset($objects[$length]);
			}
		}
		
		return $objects;
	}
	
	/**
	 * @see	wcf\system\user\object\watch\UserObjectWatchHandler::resetObjects();
	 */
	public function resetObject($objectType, $objectID) {
		$this->resetObjects($objectType, array($objectID));
	}
	
	/**
	 * Resets the object watch cache for all subscriber of the given object.
	 * 
	 * @param	string		$objectType
	 * @param	array<integer>	$objectIDs
	 */
	public function resetObjects($objectType, array $objectIDs) {
		// get object type id
		$objectTypeObj = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.user.objectWatch', $objectType);
		
		// get subscriber
		$userIDs = array();
		$conditionsBuilder = new PreparedStatementConditionBuilder();
		$conditionsBuilder->add('objectTypeID = ?', array($objectTypeObj->objectTypeID));
		$conditionsBuilder->add('objectID IN (?)', array($objectIDs));
		$sql = "SELECT		userID
			FROM		wcf".WCF_N."_user_object_watch
			".$conditionsBuilder;
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($conditionsBuilder->getParameters());
		while ($row = $statement->fetchArray()) {
			$userIDs[] = $row['userID'];
		}
		
		if (!empty($userIDs)) {
			// reset user storage
			UserStorageHandler::getInstance()->reset($userIDs, 'unreadUserObjectWatchCount');
		}
	}
	
	public function updateObject($objectType, $objectID, $notificationEventName, $notificationObjectType, IUserNotificationObject $notificationObject, array $additionalData = array()) {
		// get object type id
		$objectTypeObj = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.user.objectWatch', $objectType);
		
		// get subscriber
		$userIDs = $recipientIDs = array();
		$sql = "SELECT		userID, notification
			FROM		wcf".WCF_N."_user_object_watch
			WHERE		objectTypeID = ?
					AND objectID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array($objectTypeObj->objectTypeID, $objectID));
		while ($row = $statement->fetchArray()) {
			$userIDs[] = $row['userID'];
			if ($row['notification'] && $notificationObject->getAuthorID() != $row['userID']) $recipientIDs[] = $row['userID'];
		}
		
		if (!empty($userIDs)) {
			// reset user storage
			UserStorageHandler::getInstance()->reset($userIDs, 'unreadUserObjectWatchCount');
			
			if (!empty($recipientIDs)) {
				// create notifications
				UserNotificationHandler::getInstance()->fireEvent($notificationEventName, $notificationObjectType, $notificationObject, $recipientIDs, $additionalData);
			}
		}
	}
}
