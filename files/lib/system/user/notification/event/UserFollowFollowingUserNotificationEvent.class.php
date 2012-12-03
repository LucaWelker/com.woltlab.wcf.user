<?php
namespace wcf\system\user\notification\event;
use wcf\system\user\notification\event\AbstractUserNotificationEvent;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Notification event for followers.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.notification.event
 * @category	Community Framework
 */
class UserFollowFollowingUserNotificationEvent extends AbstractUserNotificationEvent {
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getTitle()
	 */
	public function getTitle() {
		return WCF::getLanguage()->get('wcf.user.notification.follow.shortOutput');
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getMessage()
	 */
	public function getMessage() {
		return WCF::getLanguage()->getDynamicVariable('wcf.user.notification.follow.output', array('author' => $this->author));
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getEventHash()
	 */
	public function getEventHash() {
		return StringUtil::getHash($this->packageID . '-'. $this->eventID . '-' . $this->author->userID);
	}
}
