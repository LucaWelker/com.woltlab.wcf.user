<?php
namespace wcf\page;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\menu\user\UserMenu;
use wcf\system\user\notification\UserNotificationHandler;
use wcf\system\WCF;

/**
 * Shows the notification list page.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	page
 * @category	Community Framework
 */
class NotificationListPage extends AbstractPage {
	/**
	 * list of outstanding notifications
	 * @var	array<array>
	 */
	public $notifications = array();
	
	/**
	 * @see wcf\page\AbstractPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->notifications = UserNotificationHandler::getInstance()->getNotifications(0, 0, true);
	}
	
	/**
	 * @see wcf\page\AbstractPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'notifications' => $this->notifications
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
		UserMenu::getInstance()->setActiveMenuItem('wcf.user.menu.community.notification');
		
		parent::show();
	}
}
