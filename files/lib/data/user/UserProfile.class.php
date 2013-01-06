<?php
namespace wcf\data\user;
use wcf\data\user\avatar\DefaultAvatar;
use wcf\data\user\avatar\Gravatar;
use wcf\data\user\avatar\UserAvatar;
use wcf\data\user\online\UserOnline;
use wcf\data\user\rank\UserRank;
use wcf\data\DatabaseObjectDecorator;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\breadcrumb\IBreadcrumbProvider;
use wcf\system\cache\CacheHandler;
use wcf\system\request\LinkHandler;
use wcf\system\user\online\location\UserOnlineLocationHandler;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\WCF;
use wcf\util\DateUtil;
use wcf\util\StringUtil;

/**
 * Decorates the user object and provides functions to retrieve data for user profiles.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user
 * @category	Community Framework
 */
class UserProfile extends DatabaseObjectDecorator implements IBreadcrumbProvider {
	/**
	 * @see	wcf\data\DatabaseObjectDecorator::$baseClass
	 */
	protected static $baseClass = 'wcf\data\user\User';
	
	/**
	 * cached list of user profiles
	 * @var	array<wcf\data\user\UserProfile>
	 */
	protected static $userProfiles = array();
	
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
	 * @var	wcf\data\user\avatar\IUserAvatar
	 */
	protected $avatar = null;
	
	/**
	 * user rank object
	 * @var	wcf\data\user\rank\UserRank
	 */
	protected $rank = null;
	
	/**
	 * age of this user
	 * @var	integer
	 */
	protected $__age = null;
	
	/**
	 * group data and permissions
	 * @var	array<array>
	 */
	protected $groupData = null;
	
	/**
	 * current location of this user.
	 * @var	string
	 */
	protected $currentLocation = null;
	
	const GENDER_MALE = 1;
	const GENDER_FEMALE = 2;
	
	const ACCESS_EVERYONE = 0;
	const ACCESS_REGISTERED = 1;
	const ACCESS_FOLLOWING = 2;
	const ACCESS_NOBODY = 3;
	
	/**
	 * @see	wcf\data\user\User::__toString()
	 */
	public function __toString() {
		return $this->getDecoratedObject()->__toString();
	}
	
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
				UserStorageHandler::getInstance()->loadStorage(array($this->userID));
				
				// get ids
				$data = UserStorageHandler::getInstance()->getStorage(array($this->userID), 'followingUserIDs');
				
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
					UserStorageHandler::getInstance()->update($this->userID, 'followingUserIDs', serialize($this->followingUserIDs));
				}
				else {
					$this->followingUserIDs = unserialize($data[$this->userID]);
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
				UserStorageHandler::getInstance()->loadStorage(array($this->userID));
				
				// get ids
				$data = UserStorageHandler::getInstance()->getStorage(array($this->userID), 'followerUserIDs');
				
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
					UserStorageHandler::getInstance()->update($this->userID, 'followerUserIDs', serialize($this->followerUserIDs));
				}
				else {
					$this->followerUserIDs = unserialize($data[$this->userID]);
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
				UserStorageHandler::getInstance()->loadStorage(array($this->userID));
				
				// get ids
				$data = UserStorageHandler::getInstance()->getStorage(array($this->userID), 'ignoredUserIDs');
				
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
					UserStorageHandler::getInstance()->update($this->userID, 'ignoredUserIDs', serialize($this->ignoredUserIDs));
				}
				else {
					$this->ignoredUserIDs = unserialize($data[$this->userID]);
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
			if (!$this->disableAvatar) {
				if ($this->avatarID) {
					if (!$this->fileHash) {
						// load storage data
						UserStorageHandler::getInstance()->loadStorage(array($this->userID));
						$data = UserStorageHandler::getInstance()->getStorage(array($this->userID), 'avatar');
						
						if ($data[$this->userID] === null) {
							$this->avatar = new UserAvatar($this->avatarID);
							UserStorageHandler::getInstance()->update($this->userID, 'avatar', serialize($this->avatar));
						}
						else {
							$this->avatar = unserialize($data[$this->userID]);
						}
					}
					else {
						$this->avatar = new UserAvatar(null, $this->getDecoratedObject()->data);
					}
				}
				else if (MODULE_GRAVATAR && $this->enableGravatar) {
					$this->avatar = new Gravatar($this->email);
				}
			}
			
			// use default avatar
			if ($this->avatar === null) {
				$this->avatar = new DefaultAvatar();
			}
		}
		
		return $this->avatar;
	}
	
	/**
	 * Returns true, if this user is currently online.
	 * 
	 * @return	boolean
	 */
	public function isOnline() {
		if ($this->lastActivityTime && $this->lastActivityTime > (TIME_NOW - USER_ONLINE_TIMEOUT) && (WCF::getUser()->userID == $this->userID || !$this->invisible || WCF::getUser()->getPermission('admin.general.canViewInvisible') || ($this->invisible == 2 && WCF::getUserProfileHandler()->isFollower($this->userID)))) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns the current location of this user.
	 * 
	 * @return	string
	 */
	public function getCurrentLocation() {
		if ($this->currentLocation === null) {
			$this->currentLocation = '';
			$this->currentLocation = UserOnlineLocationHandler::getInstance()->getLocation(new UserOnline(new User(null, array(
				'controller' => $this->controller,
				'objectID' => $this->locationObjectID
			))));
		}
		
		return $this->currentLocation;
	}
	
	/**
	 * Returns the last activity time.
	 * 
	 * @return	integer
	 */
	public function getLastActivityTime() {
		return max($this->lastActivityTime, $this->sessionLastActivityTime);
	}
	
	/**
	 * Returns a new user profile object.
	 * 
	 * @param	integer				$userID
	 * @return	wcf\data\user\UserProfile
	 */
	public static function getUserProfile($userID) {
		$users = self::getUserProfiles(array($userID));
		
		return (isset($users[$userID]) ? $users[$userID] : null);
	}
	
	/**
	 * Returns a list of user profiles.
	 * 
	 * @param	array				$userIDs
	 * @return	array<wcf\data\user\UserProfile>
	 */
	public static function getUserProfiles(array $userIDs) {
		$users = array();
		
		// check cache
		foreach ($userIDs as $index => $userID) {
			if (isset(self::$userProfiles[$userID])) {
				$users[$userID] = self::$userProfiles[$userID];
				unset($userIDs[$index]);
			}
		}
		
		if (!empty($userIDs)) {
			$userList = new UserProfileList();
			$userList->getConditionBuilder()->add("user_table.userID IN (?)", array($userIDs));
			$userList->sqlLimit = 0;
			$userList->readObjects();
			
			foreach ($userList as $user) {
				$users[$user->userID] = $user;
				self::$userProfiles[$user->userID] = $user;
			}
		}
		
		return $users;
	}
	
	/**
	 * Returns the user profile of the user with the given name.
	 * 
	 * @param	string				$username
	 * @return	wcf\data\user\UserProfile
	 */
	public static function getUserProfileByUsername($username) {
		$users = self::getUserProfilesByUsername(array($username));
		
		return $users[$username];
	}
	
	/**
	 * Returns the user profiles of the users with the given names.
	 * 
	 * @param	array<string>			$usernames
	 * @return	array<wcf\data\user\UserProfile>
	 */
	public static function getUserProfilesByUsername(array $usernames) {
		$users = array();
		
		// save case sensitive usernames
		$caseSensitiveUsernames = array();
		foreach ($usernames as &$username) {
			$tmp = StringUtil::toLowerCase($username);
			$caseSensitiveUsernames[$tmp] = $username;
			$username = $tmp;
		}
		unset($username);
		
		// check cache
		foreach ($usernames as $index => $username) {
			foreach (self::$userProfiles as $user) {
				if (StringUtil::toLowerCase($user->username) === $username) {
					$users[$username] = $user;
					unset($usernames[$index]);
				}
			}
		}
		
		if (!empty($usernames)) {
			$userList = new UserProfileList();
			$userList->getConditionBuilder()->add("user_table.username IN (?)", array($usernames));
			$userList->readObjects();
			
			foreach ($userList as $user) {
				$users[StringUtil::toLowerCase($user->username)] = $user;
				self::$userProfiles[$user->userID] = $user;
			}
			
			foreach ($usernames as $username) {
				if (!isset($users[$username])) {
					$users[$username] = null;
				}
			}
		}
		
		// revert usernames to original case
		foreach ($users as $username => $user) {
			unset($users[$username]);
			$users[$caseSensitiveUsernames[$username]] = $user;
		}
		
		return $users;
	}
	
	/**
	 * Returns true, if current user fulfills the required permissions.
	 * 
	 * @param	string		$name
	 * @return	boolean
	 */
	public function isAccessible($name) {
		switch ($this->$name) {
			case self::ACCESS_EVERYONE:
				return true;
			break;
			
			case self::ACCESS_REGISTERED:
				return (WCF::getUser()->userID ? true : false);
			break;
			
			case self::ACCESS_FOLLOWING:
				return ($this->isFollowing(WCF::getUser()->userID) ? true : false);
			break;
			
			case self::ACCESS_NOBODY:
				return false;
			break;
		}
	}
	
	/**
	 * Returns true, if current user profile is protected.
	 * 
	 * @return	boolean
	 */
	public function isProtected() {
		return (!WCF::getSession()->getPermission('admin.general.canViewPrivateUserOptions') && !$this->isAccessible('protectedProfile'));
	}
	
	/**
	 * Returns the age of this user.
	 *
	 * @return	integer
	 */
	public function getAge() {
		if ($this->__age === null) {
			if ($this->birthday && $this->birthdayShowYear) {
				$this->__age = DateUtil::getAge($this->birthday);
			}
			else {
				$this->__age = 0;
			}
		}
		
		return $this->__age;
	}
	
	/**
	 * Returns the age of user account in days.
	 * 
	 * @return	integer
	 */
	public function getProfileAge() {
		return (TIME_NOW - $this->registrationDate) / 86400;
	}
	
	/**
	 * Returns the value of the permission with the given name.
	 *
	 * @param	string		$permission
	 * @return	mixed		permission value
	 */
	public function getPermission($permission) {
		if ($this->groupData === null) $this->loadGroupData();
		
		if (!isset($this->groupData[$permission])) return false;
		return $this->groupData[$permission];
	}
	
	/**
	 * Returns the user title of this user.
	 */
	public function getUserTitle() {
		if ($this->userTitle) return $this->userTitle;
		if ($this->getRank()) return WCF::getLanguage()->get($this->getRank()->rankTitle);
		
		return '';
	}
	
	/**
	 * Returns the user rank.
	 * 
	 * @return	wcf\data\user\rank\UserRank
	 */
	public function getRank() {
		if ($this->rank === null) {
			if (MODULE_USER_RANK && $this->rankID) {
				if ($this->rankTitle) {
					$this->rank = new UserRank(null, array(
						'rankID' => $this->rankID,
						'groupID' => $this->groupID,
						'requiredPoints' => $this->requiredPoints,
						'rankTitle' => $this->rankTitle,
						'cssClassName' => $this->cssClassName,
						'rankImage' => $this->rankImage,
						'repeatImage' => $this->repeatImage,
						'requiredGender' => $this->requiredGender
					));
				}
				else {
					// load storage data
					UserStorageHandler::getInstance()->loadStorage(array($this->userID));
					$data = UserStorageHandler::getInstance()->getStorage(array($this->userID), 'userRank');
					
					if ($data[$this->userID] === null) {
						$this->rank = new UserRank($this->rankID);
						UserStorageHandler::getInstance()->update($this->userID, 'userRank', serialize($this->rank));
					}
					else {
						$this->rank = unserialize($data[$this->userID]);
					}
				}
			}
		}
	
		return $this->rank;
	}
	
	/**
	 * Loads group data from cache.
	 */
	protected function loadGroupData() {
		// get group ids
		$groupIDs = $this->getGroupIDs();
		$groups = implode(',', $groupIDs);
		
		// register cache resource
		$cacheName = 'userGroupPermission-'.$groups;
		CacheHandler::getInstance()->addResource(
			$cacheName,
			WCF_DIR.'cache/cache.userGroupPermission-'.StringUtil::getHash($groups).'.php',
			'wcf\system\cache\builder\UserGroupPermissionCacheBuilder'
		);
		
		// get group data from cache
		$this->groupData = CacheHandler::getInstance()->get($cacheName);
		if (isset($this->groupData['groupIDs']) && $this->groupData['groupIDs'] != $groups) {
			$this->groupData = array();
		}
	}
	
	/**
	 * Returns the old username of this user.
	 * 
	 * @return	string
	 */
	public function getOldUsername() {
		if ($this->oldUsername) {
			if ($this->lastUsernameChange + PROFILE_SHOW_OLD_USERNAME * 86400 > TIME_NOW) {
				return $this->oldUsername;
			}
		}
		
		return '';
	}
	
	/**
	 * Returns true, if this user can edit his profile.
	 * 
	 * @return	boolean
	 */
	public function canEditOwnProfile() {
		return ($this->activationCode ? false : true);
	}
	
	/**
	 * @see	wcf\system\breadcrumb\IBreadcrumbProvider::getBreadcrumb()
	 */
	public function getBreadcrumb() {
		return new Breadcrumb($this->username, LinkHandler::getInstance()->getLink('User', array(
			'object' => $this
		)));
	}
	
	/**
	 * Returns encoded email address.
	 * 
	 * @return string
	 */
	public function getEncodedEmail() {
		return StringUtil::encodeAllChars($this->email);
	}
}
