<?php
namespace wcf\system\user\object\watch;

interface IUserObjectWatch {
	public function getUnreadCount($userID);
	public function getUnreadObjects($userID, $limit = 5);
	public function getObjectIDs($userID);
	public function getObjects(array $objectIDs);
	public function validateObjectID($objectID, $userID);
}
