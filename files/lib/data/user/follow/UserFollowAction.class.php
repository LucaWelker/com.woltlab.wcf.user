<?php
namespace wcf\data\user\follow;
use wcf\data\user\UserProfile;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\IGroupedUserListAction;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\ValidateActionException;
use wcf\system\user\activity\event\UserActivityEventHandler;
use wcf\system\user\notification\object\UserFollowUserNotificationObject;
use wcf\system\user\notification\UserNotificationHandler;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\user\GroupedUserList;
use wcf\system\WCF;

/**
 * Executes follower-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.follow
 * @category	Community Framework
 */
class UserFollowAction extends AbstractDatabaseObjectAction implements IGroupedUserListAction {
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$allowGuestAccess
	 */
	protected $allowGuestAccess = array('getGroupedUserList');
	
	/**
	 * user profile object
	 * @var	wcf\data\user\UserProfile;
	 */
	public $userProfile = null;
	
	/**
	 * Validates given parameters.
	 */
	public function validateFollow() {
		if (!isset($this->parameters['data']['userID'])) {
			throw new ValidateActionException("missing parameter 'userID'");
		}
		
		// validate if you're retarded
		if ($this->parameters['data']['userID'] == WCF::getUser()->userID) {
			throw new ValidateActionException('Insufficient permissions');
		}
	}
	
	/**
	 * Follows an user.
	 * 
	 * @return	array
	 */
	public function follow() {
		$follow = UserFollow::getFollow(WCF::getUser()->userID, $this->parameters['data']['userID']);
		
		// not following right now
		if (!$follow->followID) {
			$follow = UserFollowEditor::create(array(
				'userID' => WCF::getUser()->userID,
				'followUserID' => $this->parameters['data']['userID'],
				'time' => TIME_NOW
			));
			
			// send notification
			UserNotificationHandler::getInstance()->fireEvent('following', 'com.woltlab.wcf.user.follow', new UserFollowUserNotificationObject($follow), array($follow->followUserID));
			
			// fire activity event
			$packageID = PackageDependencyHandler::getInstance()->getPackageID('com.woltlab.wcf.user');
			UserActivityEventHandler::getInstance()->fireEvent('com.woltlab.wcf.user.recentActivityEvent.follow', $packageID, $this->parameters['data']['userID']);
			
			// reset storage
			UserStorageHandler::getInstance()->reset(array($this->parameters['data']['userID']), 'followerUserIDs', 1);
			UserStorageHandler::getInstance()->reset(array(WCF::getUser()->userID), 'followingUserIDs', 1);
		}
		
		return array(
			'following' => 1
		);
	}
	
	/**
	 * @see	wcf\data\user\follow\UserFollowAction::validateFollow()
	 */
	public function validateUnfollow() {
		$this->validateFollow();
	}
	
	/**
	 * Stops following an user.
	 * 
	 * @return	array
	 */
	public function unfollow() {
		$follow = UserFollow::getFollow(WCF::getUser()->userID, $this->parameters['data']['userID']);
		
		if ($follow->followID) {
			$followEditor = new UserFollowEditor($follow);
			$followEditor->delete();
			
			// revoke notification
			UserNotificationHandler::getInstance()->revokeEvent('following', 'com.woltlab.wcf.user.follow', new UserFollowUserNotificationObject($follow));
			
			// remove activity event
			$packageID = PackageDependencyHandler::getInstance()->getPackageID('com.woltlab.wcf.user');
			UserActivityEventHandler::getInstance()->removeEvents('com.woltlab.wcf.user.recentActivityEvent.follow', $packageID, array($this->parameters['data']['userID']));
		}
		
		// reset storage
		UserStorageHandler::getInstance()->reset(array($this->parameters['data']['userID']), 'followerUserIDs', 1);
		UserStorageHandler::getInstance()->reset(array(WCF::getUser()->userID), 'followingUserIDs', 1);
		
		return array(
			'following' => 0
		);
	}
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::validateDelete()
	 */
	public function validateDelete() {
		if (empty($this->objectIDs)) {
			throw new ValidateActionException("missing parameter 'objectID'");
		}
		
		// disguise as unfollow
		$this->parameters['data']['userID'] = array_shift($this->objectIDs);
		$this->validateUnfollow();
	}
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::delete()
	 */
	public function delete() {
		// disguise as unfollow
		$this->unfollow();
	}
	
	/**
	 * @see	wcf\data\IGroupedUserListAction::validateGetGroupedUserList()
	 */
	public function validateGetGroupedUserList() {
		$this->readInteger('pageNo');
		$this->readInteger('userID');
		
		$this->userProfile = UserProfile::getUserProfile($this->parameters['userID']);
		if ($this->userProfile->isProtected()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see	wcf\data\IGroupedUserListAction::getGroupedUserList()
	 */
	public function getGroupedUserList() {
		// resolve page count
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_user_follow
			WHERE	followUserID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array($this->parameters['userID']));
		$row = $statement->fetchArray();
		$pageCount = ceil($row['count'] / 20);
		
		// get user ids
		$sql = "SELECT	userID
			FROM	wcf".WCF_N."_user_follow
			WHERE	followUserID = ?";
		$statement = WCF::getDB()->prepareStatement($sql, 20, ($this->parameters['pageNo'] - 1) * 20);
		$statement->execute(array($this->parameters['userID']));
		$userIDs = array();
		while ($row = $statement->fetchArray()) {
			$userIDs[] = $row['userID'];
		}
		
		// create group
		$group = new GroupedUserList();
		$group->addUserIDs($userIDs);
		
		// load user profiles
		GroupedUserList::loadUsers();
		
		WCF::getTPL()->assign(array(
			'groupedUsers' => array($group)
		));
		
		return array(
			'pageCount' => $pageCount,
			'template' => WCF::getTPL()->fetch('groupedUserList')
		);
	}
}
