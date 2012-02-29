<?php
namespace wcf\page;
use wcf\system\menu\page\PageMenu;

class UserListPage extends SortablePage {
	/**
	 * @see wcf\page\AbstractPage::$neededPermissions
	 */
	// public $neededPermissions = array('admin.user.canEditGroup', 'admin.user.canDeleteGroup');
	
	/**
	 * @see wcf\page\SortablePage::$defaultSortField
	 */
	public $defaultSortField = 'username';
	
	/**
	 * @see wcf\page\SortablePage::$validSortFields
	 */
	public $validSortFields = array('username', 'registrationDate');
	
	/**
	 * @see	wcf\page\MultipleLinkPage::$objectListClassName
	 */	
	public $objectListClassName = 'wcf\data\user\UserProfileList';
	
	/**
	 * @see	wcf\page\IPage::show()
	 */
	public function show() {
		PageMenu::getInstance()->setActiveMenuItem('wcf.header.menu.user');
		
		parent::show();
	}
}
