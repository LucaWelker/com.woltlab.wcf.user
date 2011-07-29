<?php
namespace wcf\system\user;
use wcf\data\user\UserProfile;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

class UserProfileHandler extends SingletonFactory {
	/**
	 * user profile object
	 * @var wcf\data\user\UserProfile
	 */
	protected $userProfile = null;
	
	/**
	 * @see wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		$this->userProfile = new UserProfile(WCF::getUser());
	}
	
	public function __call($name, $arguments) {
		return call_user_func_array(array($this->userProfile, $name), $arguments);
	}
	
	public function __get($name) {
		return $this->userProfile->$name;
	}
}
