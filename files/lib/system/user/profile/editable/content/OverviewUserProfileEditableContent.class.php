<?php
namespace wcf\system\user\profile\editable\content;
use wcf\data\user\User;
use wcf\data\user\UserAction;
use wcf\system\option\user\UserOptionHandler;
use wcf\system\WCF;

class OverviewUserProfileEditableContent implements IUserProfileEditableContent {
	public $cacheName = 'user-profile';
	
	public $cacheClass = 'wcf\system\cache\builder\OptionCacheBuilder';
	
	public $categoryFilter = array(
		'profile.aboutMe',
		'profile.personal',
		'profile.contact'
	);
	
	public $optionHandler = null;
	
	/**
	 * target user object
	 * @var	wcf\data\user\User
	 */
	public $user = null;
	
	/**
	 * @see	wcf\system\user\editable\content\IUserProfileEditableContent::setUser()
	 */
	public function setUser(User $user) {
		$this->user = $user;
	}
	
	/**
	 * @see	wcf\system\user\editable\content\IUserProfileEditableContent::prepareEdit()
	 */
	public function beginEdit() {
		$this->initOptionHandler();
		$this->optionHandler->showEmptyOptions();
		
		$optionTree = $this->optionHandler->getOptionTree();
		WCF::getTPL()->assign(array(
			'optionTree' => $optionTree
		));
		
		return WCF::getTPL()->fetch('userProfileOverviewEditable');
	}
	
	/**
	 * @see	wcf\system\user\editable\content\IUserProfileEditableContent::save()
	 */
	public function save(array $data) {
		$data = array('values' => $data);
		
		$this->initOptionHandler();
		$this->optionHandler->readUserInput($data);
		
		$this->optionHandler->validate();
		$saveOptions = $this->optionHandler->save();
		
		$userAction = new UserAction(array($this->user->userID), 'update', array(
			'options' => $saveOptions
		));
		$userAction->executeAction();
	}
	
	/**
	 * @see	wcf\system\user\editable\content\IUserProfileEditableContent::restore()
	 */
	public function restore() {
		// reload user
		$this->user = new User($this->user->userID);
		
		// reload option handler
		$this->initOptionHandler();
		$this->optionHandler->hideEmptyOptions();
		
		$options = $this->optionHandler->getOptionTree();
		WCF::getTPL()->assign(array(
			'options' => $options
		));
		
		return WCF::getTPL()->fetch('userProfileOverview');
	}
	
	/**
	 * Initializes the user option handler
	 */
	protected function initOptionHandler() {
		$this->optionHandler = new UserOptionHandler($this->cacheName, $this->cacheClass, false, '', 'profile', false);
		$this->optionHandler->setUser($this->user);
	}
}
