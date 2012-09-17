<?php
namespace wcf\data\user\object\watch;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\user\object\watch\UserObjectWatchHandler;
use wcf\system\WCF;

class ViewableUserObjectWatchList extends UserObjectWatchList {
	protected $objectTypeIDs = array();
	protected $groupedObjectIDs = null;
	protected $groupedObjects = array();
	
	/**
	 * Creates a new ViewableUserObjectWatchList object.
	 */
	public function __construct() {
		parent::__construct();
		
		// get object type ids
		$this->objectTypeIDs = UserObjectWatchHandler::getInstance()->getObjectTypeIDs();
		
		if (!empty($this->objectTypeIDs)) {
			$this->getConditionBuilder()->add('user_object_watch.objectTypeID IN (?)', array($this->objectTypeIDs));
			$this->getConditionBuilder()->add('user_object_watch.userID = ?', array(WCF::getUser()->userID));
		}
	}
	
	/**
	 * wcf\data\DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		if (empty($this->objectTypeIDs)) return 0;
		
		return parent::countObjects();
	}
	
	/**
	 * wcf\data\DatabaseObjectList::readObjectIDs()
	 */
	public function readObjectIDs() {
		$this->groupedObjectIDs = array();
		
		$sql = '';
		$parameters = array();
					
		foreach ($this->objectTypeIDs as $objectTypeID) {
			$processor = ObjectTypeCache::getInstance()->getObjectType($objectTypeID)->getProcessor();
			if (($data = $processor->getObjectIDs(WCF::getUser()->userID)) !== null) {
				if (!empty($sql)) $sql .= ' UNION ';
				$sql .= '('.$data['sql'].')';
				
				$parameters = array_merge($parameters, $data['parameters']);
			}
		}
		
		if (!empty($sql)) {
			$statement = WCF::getDB()->prepareStatement($sql.(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : ''), $this->sqlLimit, $this->sqlOffset);
			$statement->execute($parameters);
			while ($row = $statement->fetchArray()) {
				$this->objectIDs[] = array(
					'objectTypeID' => $row['objectTypeID'],
					'objectID' => $row['objectID']
				);
				
				if (!isset($this->groupedObjectIDs[$row['objectTypeID']])) $this->groupedObjectIDs[$row['objectTypeID']] = array();
				$this->groupedObjectIDs[$row['objectTypeID']][] = $row['objectID'];
			}
		}
	}
	
	/**
	 * wcf\data\DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		if ($this->groupedObjectIDs === null) {
			$this->readObjectIDs();
		}
		if (empty($this->groupedObjectIDs)) {
			return;
		}
		
		foreach ($this->groupedObjectIDs as $objectTypeID => $objectIDs) {
			$processor = ObjectTypeCache::getInstance()->getObjectType($objectTypeID)->getProcessor();
			$this->groupedObjects[$objectTypeID] = $processor->getObjects($objectIDs);
		}
		unset($this->groupedObjectIDs);
		
		foreach ($this->objectIDs as $objectIDData) {
			if (isset($this->groupedObjects[$objectIDData['objectTypeID']][$objectIDData['objectID']])) {
				$this->objects[] = $this->groupedObjects[$objectIDData['objectTypeID']][$objectIDData['objectID']];
			}
		}
	}
}
