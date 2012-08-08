<?php
namespace wcf\page;
use wcf\data\user\ignore\UserIgnoreList;
use wcf\system\menu\user\UserMenu;
use wcf\system\WCF;

/**
 * Shows a list with all users the active user ignores.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	page
 * @category	Community Framework
 */
class IgnoredUsersPage extends AbstractPage {
	/**
	 * @see wcf\page\AbstractPage::$loginRequired
	 */
	public $loginRequired = true;
	
	/**
	 * list of ignored users
	 * @var	wcf\data\user\ignore\UserIgnoreList
	 */
	public $ignoredUsers = null;
	
	/**
	 * @see wcf\page\AbstractPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->ignoredUsers = new UserIgnoreList();
		$this->ignoredUsers->sqlLimit = 100;
		$this->ignoredUsers->sqlOrderBy = "user_table.username ASC";
		$this->ignoredUsers->getConditionBuilder()->add("user_ignore.userID = ?", array(WCF::getUser()->userID));
		$this->ignoredUsers->readObjects();
	}
	
	/**
	 * @see wcf\page\AbstractPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'count' => $this->ignoredUsers->countObjects(),
			'ignoredUsers' => $this->ignoredUsers
		));
	}
	
	/**
	 * @see wcf\page\Page::show()
	 */
	public function show() {
		// set active tab
		UserMenu::getInstance()->setActiveMenuItem('wcf.user.menu.community.ignoredUsers');
		
		parent::show();
	}
}
