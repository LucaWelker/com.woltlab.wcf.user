<?php
namespace wcf\page;
use wcf\data\user\ignore\UserIgnoreList;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\menu\user\UserMenu;
use wcf\system\WCF;

/**
 * Shows the ignored users page.
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
		if (!WCF::getUser()->userID) {
			throw new PermissionDeniedException();
		}
		
		// set active tab
		UserMenu::getInstance()->setActiveMenuItem('wcf.user.menu.community.ignoredUsers');
		
		parent::show();
	}
}
