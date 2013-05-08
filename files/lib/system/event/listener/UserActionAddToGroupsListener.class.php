<?php
namespace wcf\system\event\listener;
use wcf\data\user\UserProfileAction;
use wcf\system\event\IEventListener;
use wcf\system\WCF;

/**
 * Updates user ranks.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.event.listener
 * @category	Community Framework
 */
class UserActionAddToGroupsListener implements IEventListener {
	/**
	 * @see	wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if ($eventObj->getActionName() != 'addToGroups') return;
		
		if (MODULE_USER_RANK) {
			$action = new UserProfileAction(array($eventObj->getObjects()), 'updateUserRank');
			$action->executeAction();
		}
		if (MODULE_USERS_ONLINE) {
			$action = new UserProfileAction(array($eventObj->getObjects()), 'updateUserOnlineMarking');
			$action->executeAction();
		}
	}
}
