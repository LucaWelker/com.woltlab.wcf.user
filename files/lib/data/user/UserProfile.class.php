<?php
namespace wcf\data\user;
use wcf\data\DatabaseObjectDecorator;
use wcf\system\storage\StorageHandler;
use wcf\system\WCF;

/**
 * Decorates the user object and provides functions to retrieve data for user profiles.
 *
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user
 * @category 	Community Framework
 */
class UserProfile extends DatabaseObjectDecorator {
	/**
	 * @see wcf\data\DatabaseObjectDecorator::$baseClass
	 */
	protected static $baseClass = 'wcf\data\user\User';
	
	/**
	 * user ids of friends
	 * @var array<integer>
	 */
	protected $friendIDs = null;
	
	
	protected $requestedFriendIDs = null;
	protected $requestingFriendIDs = null;
	
	/**
	 * Returns the list of friends of this user.
	 * 
	 * @return array<integer>
	 */
	public function getFriends() {
		if ($this->friendIDs === null) {
			$this->friendIDs = array();
			
			if ($this->userID) {
				// load storage data
				StorageHandler::getInstance()->loadStorage(array($this->userID), 1);
				
				// get ids
				$data = StorageHandler::getInstance()->getStorage(array($this->userID), 'friendIDs', 1);
				
				// cache does not exist or is outdated
				if ($data[$this->userID] === null) {
					$sql = "SELECT	friendUserID
						FROM	wcf".WCF_N."_user_friend
						WHERE	userID = ?";
					$statement = WCF::getDB()->prepareStatement($sql);
					$statement->execute(array($this->userID));
					while ($row = $statement->fetchArray()) {
						$this->friendIDs[] = $row['friendUserID'];
					}
					
					// update storage data
					StorageHandler::getInstance()->update($this->userID, 'friendIDs', serialize($this->friendIDs), 1);
				}
				else {
					$this->friendIDs = unserialize($data[$this->userID]);
				}
			}
		}
		
		return $this->friendIDs;
	}
	
	
	public function getRequestedFriends() {
		if ($this->requestedFriendIDs === null) {
			$this->requestedFriendIDs = array();
			
			if ($this->userID) {
				// load storage data
				StorageHandler::getInstance()->loadStorage(array($this->userID), 1);
				
				// get ids
				$data = StorageHandler::getInstance()->getStorage(array($this->userID), 'requestedFriendIDs', 1);
				
				// cache does not exist or is outdated
				if ($data[$this->userID] === null) {
					$sql = "SELECT	friendUserID
						FROM	wcf".WCF_N."_user_friend_request
						WHERE	userID = ?
							AND ignored = 0";
					$statement = WCF::getDB()->prepareStatement($sql);
					$statement->execute(array($this->userID));
					while ($row = $statement->fetchArray()) {
						$this->requestedFriendIDs[] = $row['friendUserID'];
					}
					
					// update storage data
					StorageHandler::getInstance()->update($this->userID, 'requestedFriendIDs', serialize($this->requestedFriendIDs), 1);
				}
				else {
					$this->requestedFriendIDs = unserialize($data[$this->userID]);
				}
			}
		}
		
		return $this->requestedFriendIDs;
	}
	
	public function getRequestingFriends() {
		if ($this->requestingFriendIDs === null) {
			$this->requestingFriendIDs = array();
			
			if ($this->userID) {
				// load storage data
				StorageHandler::getInstance()->loadStorage(array($this->userID), 1);
				
				// get ids
				$data = StorageHandler::getInstance()->getStorage(array($this->userID), 'requestingFriendIDs', 1);
				
				// cache does not exist or is outdated
				if ($data[$this->userID] === null) {
					$sql = "SELECT	userID
						FROM	wcf".WCF_N."_user_friend_request
						WHERE	friendUserID = ?
							AND ignored = 0";
					$statement = WCF::getDB()->prepareStatement($sql);
					$statement->execute(array($this->userID));
					while ($row = $statement->fetchArray()) {
						$this->requestedFriendIDs[] = $row['userID'];
					}
					
					// update storage data
					StorageHandler::getInstance()->update($this->userID, 'requestingFriendIDs', serialize($this->requestingFriendIDs), 1);
				}
				else {
					$this->requestedFriendIDs = unserialize($data[$this->userID]);
				}
			}
		}
		
		return $this->requestingFriendIDs;
	}

	/**
	 * Returns true, if the given user is a friend of this user.
	 * 
	 * @param	integer		$userID
	 * @return	boolean
	 */
	public function isFriend($userID) {
		return in_array($userID, $this->getFriends());
	}
	
	/**
	 * Returns true, if there is a open friend request for the given user.
	 * 
	 * @param	integer		$userID
	 * @return	boolean
	 */
	public function isRequestedFriend($userID) {
		return in_array($userID, $this->getRequestedFriends());
	}
	
	/**
	 * Returns true, if there is a open friend request by the given user.
	 * 
	 * @param	integer		$userID
	 * @return	boolean
	 */
	public function isRequestingFriend($userID) {
		return in_array($userID, $this->getRequestingFriends());
	}
}
