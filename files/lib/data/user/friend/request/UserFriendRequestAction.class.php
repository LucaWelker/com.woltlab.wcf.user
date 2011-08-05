<?php
namespace wcf\data\user\friend\request;
use wcf\data\user\friend\UserFriendEditor;
use wcf\data\user\friend\UserFriend;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\ValidateActionException;
use wcf\system\storage\StorageHandler;
use wcf\system\user\notification\UserNotificationHandler;
use wcf\system\user\notification\object\UserFriendRequestUserNotificationObject;
use wcf\system\WCF;

/**
 * Executes friend request-related actions.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.friend.request
 * @category 	Community Framework
 */
class UserFriendRequestAction extends AbstractDatabaseObjectAction {
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$className
	 */
	public $className = 'wcf\data\user\friend\request\UserFriendRequestEditor';
	
	/**
	 * @see wcf\data\AbstractDatabaseObjectAction::validateCreate()
	 */
	public function validateCreate() {
		// validate parameters and permissions
		if (isset($this->parameters['data']['userID']) || !WCF::getUser()->userID) {
			throw new ValidateActionException('Insufficient permissions');
		}
		if (!isset($this->parameters['data']['friendUserID'])) {
			throw new ValidateActionException("missing parameter 'friendUserID'");
		}
		$this->parameters['data']['friendUserID'] = intval($this->parameters['data']['friendUserID']);
		
		// try to find existing friendship 
		$friend = UserFriend::getFriend(WCF::getUser()->userID, $this->parameters['data']['friendUserID']);
		if ($friend !== null) {
			throw new ValidateActionException('given user is one of your friends already');
		}
		
		// try to find existing friend request
		$request = UserFriendRequest::getRequest(WCF::getUser()->userID, $this->parameters['data']['friendUserID']);
		if ($request !== null) {
			throw new ValidateActionException('there is a friend request for this user already');
		}
	}
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::create()
	 */
	public function create() {
		if (!isset($this->parameters['data']['userID'])) $this->parameters['data']['userID'] = WCF::getUser()->userID;
		$this->parameters['data']['time'] = TIME_NOW;
		
		$request = parent::create();
		
		// send notification
		UserNotificationHandler::getInstance()->fireEvent('create', 'com.woltlab.wcf.user.friend.request', new UserFriendRequestUserNotificationObject($request), array($request->friendUserID));
		
		// reset storage
		StorageHandler::getInstance()->reset(array($this->parameters['data']['userID']), 'requestedFriendIDs', 1);
		StorageHandler::getInstance()->reset(array($this->parameters['data']['friendUserID']), 'requestingFriendIDs', 1);
		
		return $request;
	}
	
	/**
	 * Validates the ignore function.
	 */
	public function validateIgnore() {
		$this->readObjects();
		if (!count($this->objects)) {
			throw new ValidateActionException('Invalid object id');
		}
		
		foreach ($this->objects as $object) {
			if ($object->friendUserID != WCF::getUser()->userID) {
				throw new ValidateActionException('Insufficient permissions');
			}
		}
	}
	
	/**
	 * Ignores friend requests.
	 */
	public function ignore() {
		if (!count($this->objects)) {
			$this->readObjects();
		}
		
		foreach ($this->objects as $object) {
			$object->update(array(
				'ignore' => 1
			));
			
			// reset storage
			StorageHandler::getInstance()->reset(array($object->userID), 'requestedFriendIDs', 1);
			StorageHandler::getInstance()->reset(array($object->friendUserID), 'requestingFriendIDs', 1);
		}
	}
	
	/**
	 * Validates the accept function.
	 */
	public function validateAccept() {
		$this->validateIgnore();
	}
	
	/**
	 * Accepts friend requests.
	 */
	public function accept() {
		if (!count($this->objects)) {
			$this->readObjects();
		}
		
		foreach ($this->objects as $object) {
			// add friends
			UserFriendEditor::create(array(
				'userID' => $object->userID,
				'friendUserID' => $object->friendUserID,
				'time' => TIME_NOW
			));
			UserFriendEditor::create(array(
				'userID' => $object->friendUserID,
				'friendUserID' => $object->userID,
				'time' => TIME_NOW
			));
			
			// delete obsolete request
			$object->delete();
			
			// send notification
			UserNotificationHandler::getInstance()->fireEvent('accept', 'com.woltlab.wcf.user.friend.request', new UserFriendRequestUserNotificationObject($object), array($object->userID));
			
			// reset storage
			StorageHandler::getInstance()->reset(array($object->userID), 'requestedFriendIDs', 1);
			StorageHandler::getInstance()->reset(array($object->friendUserID), 'requestingFriendIDs', 1);
			StorageHandler::getInstance()->reset(array($object->userID), 'friendIDs', 1);
			StorageHandler::getInstance()->reset(array($object->friendUserID), 'friendIDs', 1);
		}
	}
	
	/**
	 * Validates the reject function.
	 */
	public function validateReject() {
		$this->validateIgnore();
	}
	
	/**
	 * Rejects friend request.s
	 */
	public function reject() {
		if (!count($this->objects)) {
			$this->readObjects();
		}
		
		foreach ($this->objects as $object) {
			// delete rejected request
			$object->delete();
			
			// send notification
			UserNotificationHandler::getInstance()->fireEvent('reject', 'com.woltlab.wcf.user.friend.request', new UserFriendRequestUserNotificationObject($object), array($object->userID));
			
			// reset storage
			StorageHandler::getInstance()->reset(array($object->userID), 'requestedFriendIDs', 1);
			StorageHandler::getInstance()->reset(array($object->friendUserID), 'requestingFriendIDs', 1);
		}
	}
	
	/**
	 * Validates the cancel function.
	 */
	public function validateCancel() {
		$this->readObjects();
		if (!count($this->objects)) {
			throw new ValidateActionException('Invalid object id');
		}
		
		foreach ($this->objects as $object) {
			if ($object->userID != WCF::getUser()->userID) {
				throw new ValidateActionException('Insufficient permissions');
			}
		}
	}
	
	/**
	 * Cancels friend requests.
	 */
	public function cancel() {
		if (!count($this->objects)) {
			$this->readObjects();
		}
		
		foreach ($this->objects as $object) {
			// delete canceled request
			$object->delete();
			
			// revoke notification
			UserNotificationHandler::getInstance()->revokeEvent('create', 'com.woltlab.wcf.user.friend.request', new UserFriendRequestUserNotificationObject($object));
			
			// reset storage
			StorageHandler::getInstance()->reset(array($object->userID), 'requestedFriendIDs', 1);
			StorageHandler::getInstance()->reset(array($object->friendUserID), 'requestingFriendIDs', 1);
		}
	}
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::readObjects()
	 */
	protected function readObjects() {
		if (count($this->objectIDs)) {
			return parent::readObjects();
		}
		
		if (WCF::getUser()->userID) {
			// get base class
			$baseClass = call_user_func(array($this->className, 'getBaseClass'));
			
			if (isset($this->parameters['friendUserID'])) {
				$sql = "SELECT	*
					FROM	wcf".WCF_N."_user_friend_request
					WHERE	userID = ?
						AND friendUserID = ?";
				$statement = WCF::getDB()->prepareStatement($sql);
				$statement->execute(array(WCF::getUser()->userID, $this->parameters['friendUserID']));
				while ($object = $statement->fetchObject($baseClass)) {
					$this->objects[] = new $this->className($object);
				}
			}
			if (isset($this->parameters['userID'])) {
				$sql = "SELECT	*
					FROM	wcf".WCF_N."_user_friend_request
					WHERE	userID = ?
						AND friendUserID = ?";
				$statement = WCF::getDB()->prepareStatement($sql);
				$statement->execute(array($this->parameters['userID'], WCF::getUser()->userID));
				while ($object = $statement->fetchObject($baseClass)) {
					$this->objects[] = new $this->className($object);
				}
			}
		}
	}
}
