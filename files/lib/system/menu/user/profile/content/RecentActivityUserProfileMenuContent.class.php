<?php
namespace wcf\system\menu\user\profile\content;
use wcf\system\user\activity\event\UserActivityEventHandler;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * Handles user activity events.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.menu.user.profile.content
 * @category 	Community Framework
 */
class RecentActivityUserProfileMenuContent extends SingletonFactory implements IUserProfileMenuContent {
	/**
	 * @see	wcf\system\menu\user\profile\content\IUserProfileMenuContent::getContent()
	 */
	public function getContent($userID) {
		$eventList = UserActivityEventHandler::getInstance()->getEvents(array($userID));
		
		WCF::getTPL()->assign(array(
			'eventList' => $eventList,
			'userID' => $userID
		));
		
		return WCF::getTPL()->fetch('userProfileRecentActivity');
	}
}
