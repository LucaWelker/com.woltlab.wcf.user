<?php
namespace wcf\data\user\follow;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\ValidateActionException;
use wcf\system\user\activity\event\UserActivityEventHandler;
use wcf\system\user\notification\object\UserFollowUserNotificationObject;
use wcf\system\user\notification\UserNotificationHandler;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\WCF;

/**
 * Executes follower-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.follow
 * @category 	Community Framework
 */
class UserFollowAction extends AbstractDatabaseObjectAction {
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$className
	 */
	public $className = 'wcf\data\user\follow\UserFollowEditor';
	
	/**
	 * Validates given parameters.
	 */
	public function validateFollow() {
		if (!isset($this->parameters['data']['userID'])) {
			throw new ValidateActionException("missing parameter 'userID'");
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
		}
		
		// reset storage
		UserStorageHandler::getInstance()->reset(array($this->parameters['data']['userID']), 'followerUserIDs', 1);
		
		return array(
			'following' => 0
		);
	}
	
	/**
	 * @see wcf\data\AbstractDatabaseObjectAction::validateDelete()
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
	 * @see wcf\data\AbstractDatabaseObjectAction::delete()
	 */
	public function delete() {
		// disguise as unfollow
		$this->unfollow();
	}
}
