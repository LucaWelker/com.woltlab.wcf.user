<?php
namespace wcf\data\user\friend;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\WCF;

/**
 * Executes friend-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.friend
 * @category 	Community Framework
 */
class UserFriendAction extends AbstractDatabaseObjectAction {
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$className
	 */
	public $className = 'wcf\data\user\friend\UserFriendEditor';
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::validateDelete()
	 */
	public function validateDelete() {
		if (count($this->objectIDs)) {
			parrent::validateDelete();
			return;
		}
		
		if (!isset($this->parameters['data']['friendUserID'])) {
			throw new ValidateActionException('Missing object id');
		}
			
		// fetch friend id
		$sql = "SELECT	friendID
			FROM	wcf".WCF_N."_user_friend
			WHERE	(
					userID = ?
					AND friendUserID = ?
				)
				OR
				(
					userID = ?
					AND friendUserID = ?
				)";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
			WCF::getUser()->userID,
			$this->parameters['data']['friendUserID'],
			$this->parameters['data']['friendUserID'],
			WCF::getUser()->userID
		));
		while ($row = $statement->fetchArray()) {
			$this->objectIDs[] = $row['friendID'];
		}
			
		// validate if a valid friend connection (two ways) is found
		if (count($this->objectIDs) != 2) {
			throw new ValidateActionException('Invalid friend user id');
		}
		
		// read data
		$this->readObjects();
		
		if (!count($this->objects)) {
			throw new ValidateActionException('Invalid object id');
		}
	}
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::delete()
	 */
	public function delete() {
		parent::delete();
		
		// return values for friendship AJAX API
		return array(
			'isFriend' => 0,
			'isRequested' => 0,
			'isRequesting' => 0
		);
	}
}
