<?php
namespace wcf\system\user\online\location;
use wcf\data\session\Session;
use wcf\data\user\UserList;
use wcf\system\WCF;

class UserProfileLocation implements IUserOnlineLocation {
	protected $userIDs = array();
	protected $users = null;
	
	/**
	 * @see wcf\system\user\online\location\IUserOnlineLocation::cache()
	 */
	public function cache(Session $session) {
		if ($session->objectID) $this->userIDs[] = $session->objectID;
	}
	
	/**
	 * @see wcf\system\user\online\location\IUserOnlineLocation::get()
	 */
	public function get(Session $session) {
		if ($this->users === null) {
			$this->readUsers();
		}
		
		if (!isset($this->users[$session->objectID])) {
			return '';
		}
		
		return WCF::getLanguage()->getDynamicVariable('wcf.user.usersOnline.location.UserProfilePage', array('user' => $this->users[$session->objectID]));
	}
	
	protected function readUsers() {
		$this->users = array();
		
		if (empty($this->userIDs)) return;
		$this->userIDs = array_unique($this->userIDs);
		
		$userList = new UserList();
		$userList->getConditionBuilder()->add('user_table.userID IN (?)', array($this->userIDs));
	}
}