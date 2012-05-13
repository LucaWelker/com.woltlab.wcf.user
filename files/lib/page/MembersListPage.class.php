<?php
namespace wcf\page;
use wcf\system\menu\page\PageMenu;
use wcf\system\WCF;
use wcf\util\StringUtil;

class MembersListPage extends SortablePage {
	/**
	 * available letters
	 * @var string
	 */
	public static $availableLetters = '#ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	
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
	 * letter
	 * @var string
	 */
	public $letter = '';
	
	/**
	 * @see wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// letter
		if (isset($_REQUEST['letter']) && StringUtil::length($_REQUEST['letter']) == 1 && StringUtil::indexOf(self::$availableLetters, $_REQUEST['letter']) !== false) {
			$this->letter = $_REQUEST['letter'];
		}
	}
	
	/**
	 * @see wcf\page\MultipleLinkPage::initObjectList()
	 */
	protected function initObjectList() {
		parent::initObjectList();
		
		if (!empty($this->letter)) {
			// todo: we need a fallback for postgresql here
			if ($this->letter == '#') {
				$this->objectList->getConditionBuilder()->add("SUBSTRING(username,1,1) IN ('0', '1', '2', '3', '4', '5', '6', '7', '8', '9')");
			}
			else {
				$this->objectList->getConditionBuilder()->add("BINARY UPPER(SUBSTRING(username,1,1)) = ?", array($this->letter));
			}
		}
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'letters' => preg_split('//', self::$availableLetters, -1, PREG_SPLIT_NO_EMPTY),
			'letter' => $this->letter
		));
	}
	
	/**
	 * @see	wcf\page\IPage::show()
	 */
	public function show() {
		PageMenu::getInstance()->setActiveMenuItem('wcf.user.members');
		
		parent::show();
	}
}
