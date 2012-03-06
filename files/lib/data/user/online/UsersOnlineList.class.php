<?php
namespace wcf\data\user\online;
use wcf\data\session\SessionList;
use wcf\data\user\UserProfile;
use wcf\system\WCF;

/**
 * Represents a list of currently online users.
 * 
 * @author 	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.online
 * @category 	Community Framework
 */
class UsersOnlineList extends SessionList {
	/**
	 * @see	wcf\data\DatabaseObjectList::$objectClassName
	 */
	public $objectClassName = 'wcf\data\user\User';
	
	/**
	 * Creates a new UserFollowingList object.
	 */
	public function __construct() {
		parent::__construct();
		
		$this->sqlSelects .= "user_avatar.*, user_option_value.*, user_table.*";
		
		$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_user user_table ON (user_table.userID = session.userID)";
		$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_user_option_value user_option_value ON (user_option_value.userID = user_table.userID)";
		$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_user_avatar user_avatar ON (user_avatar.avatarID = user_table.avatarID)";
	}
	
	/**
	 * @see	wcf\data\DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		parent::readObjects();
		
		foreach ($this->objects as &$object) {
			$object = new UserProfile($object);
		}
	}
}
