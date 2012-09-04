<?php
namespace wcf\system\user\online\location;
use wcf\data\user\online\UserOnline;
use wcf\data\user\UserList;
use wcf\system\WCF;

class UserProfileLocation implements IUserOnlineLocation {
	protected $userIDs = array();
	protected $users = null;
	
	/**
	 * @see wcf\system\user\online\location\IUserOnlineLocation::cache()
	 */
	public function cache(UserOnline $user) {
		if ($user->objectID) $this->userIDs[] = $user->objectID;
	}
	
	/**
	 * @see wcf\system\user\online\location\IUserOnlineLocation::get()
	 */
	public function get(UserOnline $user) {
		if ($this->users === null) {
			$this->readUsers();
		}
		
		if (!isset($this->users[$user->objectID])) {
			return '';
		}
		
		return WCF::getLanguage()->getDynamicVariable('wcf.user.usersOnline.location.UserPage', array('user' => $this->users[$user->objectID]));
	}
	
	protected function readUsers() {
		$this->users = array();
		
		if (empty($this->userIDs)) return;
		$this->userIDs = array_unique($this->userIDs);
		
		$userList = new UserList();
		$userList->getConditionBuilder()->add('user_table.userID IN (?)', array($this->userIDs));
		$userList->sqlLimit = 0;
		$userList->readObjects();
		$this->users = $userList->getObjects();
	}
}