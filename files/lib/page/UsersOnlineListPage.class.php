<?php
namespace wcf\page;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\menu\page\PageMenu;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

/**
 * Shows page which lists all users who are online.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	page
 * @category	Community Framework
 */
class UsersOnlineListPage extends SortablePage {
	/**
	 * @see	wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('user.profile.canViewUsersOnlineList');
	
	/**
	 * @see	wcf\page\AbstractPage::$enableTracking
	 */
	public $enableTracking = true;
	
	/**
	 * @see	wcf\page\SortablePage::$defaultSortField
	 */
	public $defaultSortField = 'lastActivityTime';
	
	/**
	 * @see	wcf\page\SortablePage::$defaultSortOrder
	 */
	public $defaultSortOrder = 'DESC';
	
	/**
	 * @see	wcf\page\SortablePage::$validSortFields
	 */
	public $validSortFields = array('username', 'lastActivityTime', 'requestURI');
	
	/**
	 * @see	wcf\page\MultipleLinkPage::$objectListClassName
	 */	
	public $objectListClassName = 'wcf\data\user\online\UsersOnlineList';
	
	/**
	 * page locations
	 * @var	array
	 */
	public $locations = array();
	
	/**
	 * @see	wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (WCF::getSession()->getPermission('admin.user.canViewIpAddress')) {
			$this->validSortFields[] = 'ipAddress';
			$this->validSortFields[] = 'userAgent';
		}
	}
	
	/**
	 * @see	wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		// add breadcrumbs
		WCF::getBreadcrumbs()->add(new Breadcrumb(WCF::getLanguage()->get('wcf.user.members'), LinkHandler::getInstance()->getLink('MembersList')));
		
		// load locations
		foreach (ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.user.online.location') as $objectType) {
			$this->locations[$objectType->controller] = $objectType;
		}
		
		// cache data
		foreach ($this->objectList as $userOnline) {
			if (isset($this->locations[$userOnline->controller]) && $this->locations[$userOnline->controller]->getProcessor()) {
				$this->locations[$userOnline->controller]->getProcessor()->cache($userOnline);
			}
		}
		
		// set locations
		foreach ($this->objectList as $userOnline) {
			if (isset($this->locations[$userOnline->controller])) {
				if ($this->locations[$userOnline->controller]->getProcessor()) {
					$userOnline->setLocation($this->locations[$userOnline->controller]->getProcessor()->get($userOnline, $this->locations[$userOnline->controller]->languagevariable));
				}
				else {
					$userOnline->setLocation(WCF::getLanguage()->get($this->locations[$userOnline->controller]->languagevariable));
				}
			}
		}
	}
	
	/**
	 * @see	wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'sidebarCollapsed' => UserCollapsibleContentHandler::getInstance()->isCollapsed('com.woltlab.wcf.collapsibleSidebar', 'com.woltlab.wcf.user.UsersOnlineList'),
			'sidebarName' => 'com.woltlab.wcf.user.UsersOnlineList',
		));
	}
	
	/**
	 * Reads object list.
	 */	
	protected function readObjects() {
		$this->objectList->sqlLimit = 0;
		if ($this->sqlOrderBy) $this->objectList->sqlOrderBy = $this->sqlOrderBy;
		$this->objectList->readObjects();
	}
	
	/**
	 * @see	wcf\page\IPage::show()
	 */
	public function show() {
		PageMenu::getInstance()->setActiveMenuItem('wcf.user.members');
		
		parent::show();
	}
}
