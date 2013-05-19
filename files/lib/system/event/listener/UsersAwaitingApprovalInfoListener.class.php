<?php
namespace wcf\system\event\listener;
use wcf\system\event\IEventListener;
use wcf\system\WCF;

/**
 * Show the users awaiting approval info box.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.event.listener
 * @category	Community Framework
 */
class UsersAwaitingApprovalInfoListener implements IEventListener {
	/**
	 * @see	wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		$usersAwaitingApproval = 0;
		
		if (REGISTER_ACTIVATION_METHOD == 2) {
			$sql = "SELECT	COUNT(*) AS count
				FROM	wcf".WCF_N."_user
				WHERE	activationCode <> 0";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute();
			$row = $statement->fetchArray();
			$usersAwaitingApproval = $row['count'];
		}
		WCF::getTPL()->assign('usersAwaitingApproval', $usersAwaitingApproval);
	}
}
