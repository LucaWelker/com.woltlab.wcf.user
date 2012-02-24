<?php
namespace wcf\page;
use wcf\data\user\follow\UserFollowingList;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\menu\user\UserMenu;
use wcf\system\WCF;

/**
 * Shows the following page.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	page
 * @category	Community Framework
 */
class FollowingPage extends AbstractPage {
	/**
	 * list of following users
	 * @var	wcf\data\user\follow\UserFollowingList
	 */
	public $following = array();
	
	/**
	 * @see wcf\page\AbstractPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->following = new UserFollowingList();
		$this->following->sqlLimit = 100;
		$this->following->sqlOrderBy = "user_table.username ASC";
		$this->following->getConditionBuilder()->add("user_follow.userID = ?", array(WCF::getUser()->userID));
		$this->following->readObjects();
	}
	
	/**
	 * @see wcf\page\AbstractPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'count' => $this->following->countObjects(),
			'following' => $this->following
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
		UserMenu::getInstance()->setActiveMenuItem('wcf.user.menu.community.following');
		
		parent::show();
	}
}
