<?php
namespace wcf\data\user\object\watch;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\UserInputException;
use wcf\system\user\object\watch\UserObjectWatchHandler;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\WCF;

/**
 * Executes watched object-related actions.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.object.watch
 * @category	Community Framework
 */
class UserObjectWatchAction extends AbstractDatabaseObjectAction {
	/**
	 * cached user object watch object
	 * @var	wcf\data\user\object\watch\UserObjectWatch
	 */
	protected $__userObjectWatch = null;
	
	/**
	 * Adds a subscription.
	 */
	public function subscribe() {
		$objectType = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.user.objectWatch', $this->parameters['data']['objectType']);
		
		UserObjectWatchEditor::create(array(
			'userID' => WCF::getUser()->userID,
			'objectID' => intval($this->parameters['data']['objectID']),
			'objectTypeID' => $objectType->objectTypeID
		));
		
		// reset user storage
		$objectType->getProcessor()->resetUserStorage(array(WCF::getUser()->userID));
	}
	
	/**
	 * Removes a subscription.
	 */
	public function unsubscribe() {
		$objectType = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.user.objectWatch', $this->parameters['data']['objectType']);
		
		if ($this->__userObjectWatch !== null) $userObjectWatch = $this->__userObjectWatch;
		else {
			$userObjectWatch = UserObjectWatch::getUserObjectWatch($objectType->objectTypeID, WCF::getUser()->userID, intval($this->parameters['data']['objectID']));
		}
		$editor = new UserObjectWatchEditor($userObjectWatch);
		$editor->delete();
		
		// reset user storage
		$objectType->getProcessor()->resetUserStorage(array(WCF::getUser()->userID));
	}
	
	/**
	 * Validates the delete action.
	 */
	public function validateDelete() {
		$this->__validatePermission();
	}
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::delete()
	 */
	public function delete() {
		parent::delete();
		
		// reset user storage
		$objectTypes = array();
		foreach ($this->objects as $object) {
			if (!isset($objectType[$object->objectTypeID])) {
				$objectType[$object->objectTypeID] = ObjectTypeCache::getInstance()->getObjectType($object->objectTypeID);
			}
			
			$objectType[$object->objectTypeID]->getProcessor()->resetUserStorage(array(WCF::getUser()->userID));
		}
	}
	
	/**
	 * Validates the subscribe action.
	 */
	public function validateSubscribe() {
		$this->__validateSubscribe();
		
		if ($this->__userObjectWatch !== null) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * Validates the unsubscribe action.
	 */
	public function validateUnsubscribe() {
		$this->__validateSubscribe();
		
		if ($this->__userObjectWatch === null) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * Validates the enable notification action.
	 */
	public function validateEnableNotification() {
		$this->__validatePermission();
	}
	
	/**
	 * Validates the disable notification action.
	 */
	public function validateDisableNotification() {
		$this->__validatePermission();
	}
	
	/**
	 * Enables the notification for a watch objects.
	 */
	public function enableNotification() {
		if (empty($this->objects)) {
			$this->readObjects();
		}
		
		foreach ($this->objects as $objectWatch) {
			$objectWatch->update(array(
				'notification' => 1
			));
		}
	}
	
	/**
	 * Disables the notification for a watch objects.
	 */
	public function disableNotification() {
		if (empty($this->objects)) {
			$this->readObjects();
		}
		
		foreach ($this->objects as $objectWatch) {
			$objectWatch->update(array(
				'notification' => 0
			));
		}
	}
	
	/**
	 * Validates the subscribe action.
	 */
	protected function __validateSubscribe() {
		// check parameters
		if (!isset($this->parameters['data']['objectType']) || !isset($this->parameters['data']['objectID'])) {
			throw new UserInputException('objectType');
		}
		
		// validate object type
		$objectType = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.user.objectWatch', $this->parameters['data']['objectType']);
		if ($objectType === null) {
			throw new UserInputException('objectType');
		}
		
		// validate object id
		$objectType->getProcessor()->validateObjectID(intval($this->parameters['data']['objectID']));
		
		// get existing subscription
		$this->__userObjectWatch = UserObjectWatch::getUserObjectWatch($objectType->objectTypeID, WCF::getUser()->userID, intval($this->parameters['data']['objectID']));
	}
	
	/**
	 * Provides a default validation.
	 */
	protected function __validatePermission() {
		// read objects
		if (empty($this->objects)) {
			$this->readObjects();
			
			if (empty($this->objects)) {
				throw new UserInputException('objectIDs');
			}
		}
		
		foreach ($this->objects as $object) {
			if ($object->userID != WCF::getUser()->userID) {
				throw new UserInputException('objectIDs');
			}
		}
	}
}
