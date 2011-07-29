<?php
namespace wcf\data\user;
use wcf\data\DatabaseObjectDecorator;
use wcf\system\storage\StorageHandler;

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
	protected $friendIDs = array();
	
	
	protected $requestedFriendIDs = array();
	protected $requestingFriendIDs = array();
	
	/**
	 * Returns the list of friends of this user.
	 * 
	 * @return array<integer>
	 */
	public function getFriends() {
		if ($this->friendIDs === null) {
			// load storage data
			StorageHandler::getInstance()->loadStorage(array($this->userID), 1);
			
			// get group ids
			$data = StorageHandler::getInstance()->getStorage(array($this->userID), 'friendIDs', 1);
			
			// cache does not exist or is outdated
			if ($data[$this->userID] === null) {
				$this->friendIDs = array();
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
		
		return $this->friendIDs;
	}
	
	
	public function getRequestedFriends() {}
	public function getRequestingFriends() {}

	/**
	 * Returns true, if the given user is a friend of this user.
	 * 
	 * @param	integer		$userID
	 * @return	boolean
	 */
	public function isFriend($userID) {
		return in_array($userID, $this->getFriends());
	}
	
	
	public function isRequestedFriend($userID) {}
	
	public function isRequestingFriend($userID) {}
}
