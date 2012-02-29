<?php
namespace wcf\page;
use wcf\data\user\follow\UserFollowerList;
use wcf\data\user\follow\UserFollowingList;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\user\UserProfile;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\exception\IllegalLinkException;
use wcf\system\menu\page\PageMenu;
use wcf\system\request\LinkHandler;
use wcf\system\menu\user\profile\UserProfileMenu;
use wcf\system\WCF;

/**
 * Shows the user profile page.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	page
 * @category 	Community Framework
 */
class UserPage extends AbstractPage {
	/**
	 * overview editable content object type
	 * @var	wcf\data\object\type\ObjectType
	 */
	public $objectType = null;
	
	/**
	 * profile content for active menu item
	 * @var	string
	 */
	public $profileContent = '';
	
	/**
	 * @see wcf\page\AbstractPage::$templateName
	 */
	public $templateName = 'user';
	
	/**
	 * user id
	 * @var integer
	 */
	public $userID = 0;
	
	/**
	 * user object
	 * @var wcf\data\user\UserProfile
	 */
	public $user = null;
	
	/**
	 * follower list
	 * @var wcf\data\user\follow\UserFollowerList
	 */
	public $followerList = null;
	
	/**
	 * following list
	 * @var wcf\data\user\follow\UserFollowingList
	 */
	public $followingList = null;
		
	/**
	 * @see wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['id'])) $this->userID = intval($_REQUEST['id']);
		$this->user = UserProfile::getUserProfile($this->userID);
		if ($this->user === null) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		// add breadcrumbs
		WCF::getBreadcrumbs()->add(new Breadcrumb(WCF::getLanguage()->get('wcf.user.members'), LinkHandler::getInstance()->getLink('MembersList')));
		
		// get profile content
		$activeMenuItem = UserProfileMenu::getInstance()->getActiveMenuItem();
		$contentManager = $activeMenuItem->getContentManager();
		$this->profileContent = $contentManager->getContent($this->user->userID);
		$this->objectType = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.user.profileEditableContent', 'com.woltlab.wcf.user.profileOverview');
		
		// get followers
		$this->followerList = new UserFollowerList();
		$this->followerList->getConditionBuilder()->add('user_follow.followUserID = ?', array($this->userID));
		$this->followerList->sqlLimit = 9;
		$this->followerList->readObjects();
		
		// get following
		$this->followingList = new UserFollowingList();
		$this->followingList->getConditionBuilder()->add('user_follow.userID = ?', array($this->userID));
		$this->followingList->sqlLimit = 9;
		$this->followingList->readObjects();
		
		// get visitors
		
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'overviewObjectType' => $this->objectType,
			'profileContent' => $this->profileContent,
			'userID' => $this->userID,
			'user' => $this->user,
			'followers' => $this->followerList->getObjects(),
			'followerCount' => $this->followerList->countObjects(),
			'following' => $this->followingList->getObjects(),
			'followingCount' => $this->followingList->countObjects(),
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
