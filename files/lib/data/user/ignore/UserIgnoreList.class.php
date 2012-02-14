<?php
namespace wcf\data\user\ignore;
use wcf\data\user\UserProfile;
use wcf\data\DatabaseObjectList;

/**
 * Represents a list of ignored users.
 * 
 * @author 	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.ignore
 * @category 	Community Framework
 */
class UserIgnoreList extends DatabaseObjectList {
	/**
	 * @see	wcf\data\DatabaseObjectList::$className
	 */
	public $className = 'wcf\data\user\ignore\UserIgnore';
	
	/**
	 * @see	wcf\data\DatabaseObjectList::$objectClassName
	 */
	public $objectClassName = 'wcf\data\user\User';
	
	/**
	 * Creates a new UserIgnoreList object.
	 */
	public function __construct() {
		parent::__construct();
	
		$this->sqlSelects .= "user_table.username, user_table.email, user_table.disableAvatar";
		$this->sqlSelects .= ", user_avatar.*";
	
		$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_user user_table ON (user_table.userID = user_ignore.ignoreUserID)";
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
