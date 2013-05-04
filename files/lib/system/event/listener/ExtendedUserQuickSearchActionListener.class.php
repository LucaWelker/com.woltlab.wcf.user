<?php
namespace wcf\system\event\listener;
use wcf\system\event\IEventListener;
use wcf\system\WCF;

/**
 * Provides more special search options.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.event.listener
 * @category	Community Framework
 */
class ExtendedUserQuickSearchActionListener implements IEventListener {
	/**
	 * @see	wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		switch ($eventObj->mode) {
			case 'disabled':
				$eventObj->sortField = 'registrationDate';
				$eventObj->sortOrder = 'DESC';
				$sql = "SELECT		user_table.userID
					FROM		wcf".WCF_N."_user user_table
					LEFT JOIN	wcf".WCF_N."_user_option_value option_value
					ON		(option_value.userID = user_table.userID)
					WHERE		activationCode <> ?
					ORDER BY	user_table.registrationDate DESC";
				$statement = WCF::getDB()->prepareStatement($sql, $eventObj->maxResults);
				$statement->execute(array(0));
				while ($row = $statement->fetchArray()) {
					$eventObj->matches[] = $row['userID'];
				}
				break;
				
			case 'disabledAvatars':
				$sql = "SELECT		user_table.userID
					FROM		wcf".WCF_N."_user user_table
					LEFT JOIN	wcf".WCF_N."_user_option_value option_value
					ON		(option_value.userID = user_table.userID)
					WHERE		disableAvatar = ?";
				$statement = WCF::getDB()->prepareStatement($sql, $eventObj->maxResults);
				$statement->execute(array(1));
				while ($row = $statement->fetchArray()) {
					$eventObj->matches[] = $row['userID'];
				}
				break;
			
			case 'disabledSignatures':
				$sql = "SELECT		user_table.userID
					FROM		wcf".WCF_N."_user user_table
					LEFT JOIN	wcf".WCF_N."_user_option_value option_value
					ON		(option_value.userID = user_table.userID)
					WHERE		disableSignature = ?";
				$statement = WCF::getDB()->prepareStatement($sql, $eventObj->maxResults);
				$statement->execute(array(1));
				while ($row = $statement->fetchArray()) {
					$eventObj->matches[] = $row['userID'];
				}
				break;
		}
	}
}
