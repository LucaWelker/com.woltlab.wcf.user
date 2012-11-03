<?php
namespace wcf\data\user\object\watch;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\ValidateActionException;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\WCF;

/**
 * Executes watched object-related actions.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
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
		UserStorageHandler::getInstance()->reset(array(WCF::getUser()->userID), 'userObjectWatchTypeIDs');
		UserStorageHandler::getInstance()->reset(array(WCF::getUser()->userID), 'unreadUserObjectWatchCount');
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
		UserStorageHandler::getInstance()->reset(array(WCF::getUser()->userID), 'userObjectWatchTypeIDs');
		UserStorageHandler::getInstance()->reset(array(WCF::getUser()->userID), 'unreadUserObjectWatchCount');
	}
	
	/**
	 * Validates the delete action.
	 */
	public function validateDelete() {
		// read objects
		if (empty($this->objects)) {
			$this->readObjects();
			
			if (empty($this->objects)) {
				throw new ValidateActionException('Invalid object id');
			}
		}
		
		foreach ($this->objects as $object) {
			if ($object->userID != WCF::getUser()->userID) {
				throw new ValidateActionException('Invalid object id');
			}
		}
	}
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::delete()
	 */
	public function delete() {
		parent::delete();
		
		// reset user storage
		UserStorageHandler::getInstance()->reset(array(WCF::getUser()->userID), 'userObjectWatchTypeIDs');
		UserStorageHandler::getInstance()->reset(array(WCF::getUser()->userID), 'unreadUserObjectWatchCount');
	}
	
	/**
	 * Validates the subscribe action.
	 */
	public function validateSubscribe() {
		$this->__validateSubscribe();
		
		if ($this->__userObjectWatch !== null) {
			throw new ValidateActionException('Given object is already subscribed');
		}
	}
	
	/**
	 * Validates the unsubscribe action.
	 */
	public function validateUnsubscribe() {
		$this->__validateSubscribe();
		
		if ($this->__userObjectWatch === null) {
			throw new ValidateActionException('Given object is not subscribed');
		}
	}
	
	/**
	 * Validates the subscribe action.
	 */
	protected function __validateSubscribe() {
		// check parameters
		if (!isset($this->parameters['data']['objectType']) || !isset($this->parameters['data']['objectID'])) {
			throw new ValidateActionException('Missing parameters');
		}
		
		// validate object type
		$objectType = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.user.objectWatch', $this->parameters['data']['objectType']);
		if ($objectType === null) {
			throw new ValidateActionException('Invalid object type given');
		}
		
		// validate object id
		$objectType->getProcessor()->validateObjectID(intval($this->parameters['data']['objectID']), WCF::getUser()->userID);
		
		// get existing subscription
		$this->__userObjectWatch = UserObjectWatch::getUserObjectWatch($objectType->objectTypeID, WCF::getUser()->userID, intval($this->parameters['data']['objectID']));
	}
}
