<?php
namespace wcf\system\user\object\watch;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\application\ApplicationHandler;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

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
				UserStorageHandler::getInstance()->update($userID, 'userObjectWatchTypeIDs', serialize($this->objectTypeIDs[$userID]), ApplicationHandler::getInstance()->getPrimaryApplication()->packageID);
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
				UserStorageHandler::getInstance()->update($userID, 'unreadUserObjectWatchCount', serialize($this->unreadObjectCount[$userID]), ApplicationHandler::getInstance()->getPrimaryApplication()->packageID);
			}
			else {
				$this->unreadObjectCount[$userID] = unserialize($data[$userID]);
			}
		}
		
		return $this->unreadObjectCount[$userID];
	}
}
