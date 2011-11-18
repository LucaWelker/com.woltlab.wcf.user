<?php
namespace wcf\system\user\profile\editable\content;
use wcf\data\user\User;
use wcf\system\user\option\UserOptions;
use wcf\system\WCF;

class OverviewUserProfileEditableContent implements IUserProfileEditableContent {
	public $categoryFilter = array(
		'profile.aboutMe',
		'profile.personal',
		'profile.contact'
	);
	
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
		// build cached selection
		UserOptions::getInstance()->applyFilter($this->categoryFilter);
		
		// filter by category
		UserOptions::getInstance()->applyFilter($this->categoryFilter);
		
		// get options
		$options = array();
		foreach ($this->categoryFilter as $categoryName) {
			$userOptions = UserOptions::getInstance()->getCategoryOptions($this->user, $categoryName);
			if (!empty($userOptions)) {
				$options[$categoryName] = $userOptions;
			}
		}
		die('<pre>'.print_r($options, true));
		WCF::getTPL()->assign(array(
			'options' => $options
		));
		
		return WCF::getTPL()->fetch('userProfileOverview');
	}
	
	/**
	 * @see	wcf\system\user\editable\content\IUserProfileEditableContent::save()
	 */
	public function save(array $data) {
	}
	
	/**
	 * @see	wcf\system\user\editable\content\IUserProfileEditableContent::restore()
	 */
	public function restore() {
	}
}
