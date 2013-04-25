<?php
namespace wcf\system\event\listener;
use wcf\acp\form\UserEditForm;
use wcf\data\user\User;
use wcf\data\user\UserEditor;
use wcf\data\user\UserProfileAction;
use wcf\system\event\IEventListener;

/**
 * Handles user ranks in user administration.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.event.listener
 * @category	Community Framework
 */
class UserAddFormUserRankListener implements IEventListener {
	/**
	 * @see	wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (!MODULE_USER_RANK && !MODULE_USERS_ONLINE) return;
		
		if ($eventObj instanceof UserEditForm) {
			$user = new User($eventObj->userID);
		}
		else {
			$returnValues = $eventObj->objectAction->getReturnValues();
			$user = $returnValues['returnValues'];
		}
		
		$editor = new UserEditor($user);
		if (MODULE_USER_RANK) {
			$action = new UserProfileAction(array($editor), 'updateUserRank');
			$action->executeAction();
		}
		if (MODULE_USERS_ONLINE) {
			$action = new UserProfileAction(array($editor), 'updateUserOnlineMarking');
			$action->executeAction();
		}
	}
}
