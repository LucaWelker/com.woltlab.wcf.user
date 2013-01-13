<?php
namespace wcf\data\user\object\watch;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\UserInputException;
use wcf\system\user\object\watch\UserObjectWatchHandler;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\WCF;

/**
 * Executes watched object-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.object.watch
 * @category	Community Framework
 */
class UserObjectWatchAction extends AbstractDatabaseObjectAction {
	/**
	 * object type object
	 * @var	wcf\data\object\type\ObjectType
	 */
	protected $objectType = null;
	
	/**
	 * user object watch object
	 * @var	wcf\data\user\object\watch\UserObjectWatch
	 */
	protected $userObjectWatch = null;
	
	/**
	 * Validates parameters to manage a subscription.
	 */
	public function validateManageSubscription() {
		$this->readInteger('objectID');
		$this->readString('objectType');
		
		// validate object type
		$this->objectType = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.user.objectWatch', $this->parameters['objectType']);
		if ($this->objectType === null) {
			throw new UserInputException('objectType');
		}
		
		// validate object id
		$this->objectType->getProcessor()->validateObjectID($this->parameters['objectID']);
		
		// get existing subscription
		$this->userObjectWatch = UserObjectWatch::getUserObjectWatch($this->objectType->objectTypeID, WCF::getUser()->userID, $this->parameters['objectID']);
	}
	
	/**
	 * Returns a form to manage a subscription.
	 * 
	 * @return	array
	 */
	public function manageSubscription() {
		WCF::getTPL()->assign(array(
			'objectType' => $this->objectType,
			'userObjectWatch' => $this->userObjectWatch
		));
		
		return array(
			'objectID' => $this->parameters['objectID'],
			'template' => WCF::getTPL()->fetch('manageSubscription')
		);
	}
	
	/**
	 * Validates parameters to save subscription state.
	 */
	public function validateSaveSubscription() {
		$this->readBoolean('enableNotification');
		$this->readBoolean('subscribe');
		
		$this->validateManageSubscription();
	}
	
	/**
	 * Saves subscription state.
	 */
	public function saveSubscription() {
		// subscribe
		if ($this->parameters['subscribe']) {
			// newly subscribed
			if ($this->userObjectWatch === null) {
				UserObjectWatchEditor::create(array(
					'notification' => ($this->parameters['enableNotification'] ? 1 : 0),
					'objectID' => $this->parameters['objectID'],
					'objectTypeID' => $this->objectType->objectTypeID,
					'userID' => WCF::getUser()->userID
				));
			}
			else if ($this->userObjectWatch->notification != $this->parameters['enableNotification']) {
				// update notification type
				$editor = new UserObjectWatchEditor($this->userObjectWatch);
				$editor->update(array(
					'notification' => ($this->parameters['enableNotification'] ? 1 : 0)
				));
			}
			
			// reset user storage
			$this->objectType->getProcessor()->resetUserStorage(array(WCF::getUser()->userID));
		}
		else if ($this->userObjectWatch !== null) {
			// unsubscribe
			$editor = new UserObjectWatchEditor($this->userObjectWatch);
			$editor->delete();
			
			// reset user storage
			$this->objectType->getProcessor()->resetUserStorage(array(WCF::getUser()->userID));
		}
	}
}
