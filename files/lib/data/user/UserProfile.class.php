<?php
namespace wcf\data\user;
use wcf\data\user\avatar\Gravatar;
use wcf\data\user\avatar\UserAvatar;
use wcf\data\DatabaseObjectDecorator;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\WCF;

define('MODULE_AVATAR', 1);
define('MODULE_GRAVATAR', 1);

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
	 * list of ignored user ids
	 * @var	array<integer>
	 */
	protected $ignoredUserIDs = null;
	
	/**
	 * list of follower user ids
	 * @var	array<integer>
	 */
	protected $followerUserIDs = null;
	
	/**
	 * list of following user ids
	 * @var	array<integer>
	 */
	protected $followingUserIDs = null;
	
	/**
	 * user avatar
	 * @var wcf\data\user\avatar\IUserAvatar
	 */
	protected $avatar = null;
	
	/**
	 * Returns a list of all user ids being followed by current user.
	 * 
	 * @return	array<integer>
	 */
	public function getFollowingUsers() {
		if ($this->followingUserIDs === null) {
			$this->followingUserIDs = array();
			
			if ($this->userID) {
				// load storage data
				UserStorageHandler::getInstance()->loadStorage(array($this->userID), 1);
				
				// get ids
				$data = UserStorageHandler::getInstance()->getStorage(array($this->userID), 'followingUserIDs', 1);
				
				// cache does not exist or is outdated
				if ($data[$this->userID] === null) {
					$sql = "SELECT	followUserID
						FROM	wcf".WCF_N."_user_follow
						WHERE	userID = ?";
					$statement = WCF::getDB()->prepareStatement($sql);
					$statement->execute(array($this->userID));
					while ($row = $statement->fetchArray()) {
						$this->followingUserIDs[] = $row['followUserID'];
					}
					
					// update storage data
					UserStorageHandler::getInstance()->update($this->userID, 'followingUserIDs', serialize($this->followingUserIDs), 1);
				}
				else {
					$this->followingUserIDs = unserialize($data[$user->userID]);
				}
			}
		}
		
		return $this->followingUserIDs;
	}
	
	/**
	 * Returns a list of user ids following current user.
	 * 
	 * @return	array<integer>
	 */
	public function getFollowers() {
		if ($this->followerUserIDs === null) {
			$this->followerUserIDs = array();
			
			if ($this->userID) {
				// load storage data
				UserStorageHandler::getInstance()->loadStorage(array($this->userID), 1);
				
				// get ids
				$data = UserStorageHandler::getInstance()->getStorage(array($this->userID), 'followerUserIDs', 1);
				
				// cache does not exist or is outdated
				if ($data[$this->userID] === null) {
					$sql = "SELECT	userID
						FROM	wcf".WCF_N."_user_follow
						WHERE	followUserID = ?";
					$statement = WCF::getDB()->prepareStatement($sql);
					$statement->execute(array($this->userID));
					while ($row = $statement->fetchArray()) {
						$this->followerUserIDs[] = $row['userID'];
					}
					
					// update storage data
					UserStorageHandler::getInstance()->update($this->userID, 'followerUserIDs', serialize($this->followerUserIDs), 1);
				}
				else {
					$this->followerUserIDs = unserialize($data[$user->userID]);
				}
			}
		}
		
		return $this->followerUserIDs;
	}
	
	/**
	 * Returns a list of ignored user ids.
	 * 
	 * @return	array<integer>
	 */
	public function getIgnoredUsers() {
		if ($this->ignoredUserIDs === null) {
			$this->ignoredUserIDs = array();
			
			if ($this->userID) {
				// load storage data
				UserStorageHandler::getInstance()->loadStorage(array($this->userID), 1);
				
				// get ids
				$data = UserStorageHandler::getInstance()->getStorage(array($this->userID), 'ignoredUserIDs', 1);
				
				// cache does not exist or is outdated
				if ($data[$this->userID] === null) {
					$sql = "SELECT	ignoreUserID
						FROM	wcf".WCF_N."_user_ignore
						WHERE	userID = ?";
					$statement = WCF::getDB()->prepareStatement($sql);
					$statement->execute(array($this->userID));
					while ($row = $statement->fetchArray()) {
						$this->ignoredUserIDs[] = $row['ignoreUserID'];
					}
					
					// update storage data
					UserStorageHandler::getInstance()->update($this->userID, 'ignoredUserIDs', serialize($this->ignoredUserIDs), 1);
				}
				else {
					$this->ignoredUserIDs = unserialize($data[$user->userID]);
				}
			}
		}
		
		return $this->ignoredUserIDs;
	}
	
	/**
	 * Returns true, if current user is following given user id.
	 * 
	 * @param	integer		$userID
	 * @return	boolean
	 */
	public function isFollowing($userID) {
		return in_array($userID, $this->getFollowingUsers());
	}
	
	/**
	 * Returns true, if given user ids follows current user.
	 * 
	 * @param	integer		$userID
	 * @return	boolean
	 */
	public function isFollower($userID) {
		return in_array($userID, $this->getFollowers());
	}
	
	/**
	 * Returns true, if given user is ignored.
	 * 
	 * @param	integer		$userID
	 * @return	boolean
	 */
	public function isIgnoredUser($userID) {
		return in_array($userID, $this->getIgnoredUsers());
	}
	
	/**
	 * Gets the user's avatar.
	 * 
	 * @return	wcf\data\user\avatar\IUserAvatar
	 */ 
	public function getAvatar() {
		if ($this->avatar === null) {
			if (MODULE_AVATAR && !$this->disableAvatar && (!WCF::getUser()->userID || true/*WCF::getUser()->showAvatar*/) && ($this->userID == WCF::getUser()->userID || true /*WCF::getUser()->getPermission('user.profile.avatar.canViewAvatar')*/)) {
				if ($this->avatarID) {
					$this->avatar = new UserAvatar(null, $this->data);
				}
				else if (MODULE_GRAVATAR) {
					$this->avatar = new Gravatar($this->email);
				}
			}
		}
		
		return $this->avatar;
	}
}
