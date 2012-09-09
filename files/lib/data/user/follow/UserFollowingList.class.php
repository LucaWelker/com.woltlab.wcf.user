<?php
namespace wcf\data\user\follow;

/**
 * Represents a list of following users.
 * 
 * @author 	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.follow
 * @category 	Community Framework
 */
class UserFollowingList extends UserFollowerList {
	/**
	 * @see	wcf\data\DatabaseObjectList::$useQualifiedShorthand
	 */
	public $useQualifiedShorthand = false;
	
	/**
	 * @see	wcf\data\DatabaseObjectList::__construct()
	 */
	public function __construct() {
		UserFollowList::__construct();
		
		$this->sqlSelects .= "user_avatar.*, user_follow.followID, user_follow.followUserID AS userID, user_table.username, user_table.email, user_table.disableAvatar";
		
		$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_user user_table ON (user_table.userID = user_follow.followUserID)";
		$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_user_avatar user_avatar ON (user_avatar.avatarID = user_table.avatarID)";
	}
}
