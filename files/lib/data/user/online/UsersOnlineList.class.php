<?php
namespace wcf\data\user\online;
use wcf\data\session\SessionList;
use wcf\data\user\User;
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
	 * @see	wcf\data\DatabaseObjectList::$sqlOrderBy
	 */
	public $sqlOrderBy = 'user_table.username';
	
	/**
	 * users online stats
	 * @var array
	 */
	public $stats = array(
		'total' => 0,
		'invisible' => 0,
		'members' => 0,
		'guests' => 0
	);
	
	/**
	 * Creates a new UserFollowingList object.
	 */
	public function __construct() {
		parent::__construct();
		
		$this->sqlSelects .= "user_avatar.*, user_option_value.*, user_group.userOnlineMarking, user_table.*";
		
		$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_user user_table ON (user_table.userID = session.userID)";
		$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_user_option_value user_option_value ON (user_option_value.userID = user_table.userID)";
		$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_user_avatar user_avatar ON (user_avatar.avatarID = user_table.avatarID)";
		$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_user_group user_group ON (user_group.groupID = user_table.userOnlineGroupID)";
		
		$this->getConditionBuilder()->add('session.packageID = ?', array(PACKAGE_ID));
		$this->getConditionBuilder()->add('session.lastActivityTime > ?', array(TIME_NOW - USER_ONLINE_TIMEOUT));
	}
	
	/**
	 * @see	wcf\data\DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		parent::readObjects();
		
		$objects = $this->objects;
		$this->indexToObject = $this->objects = array();
		foreach ($objects as $object) {
			if (self::isVisible($object->userID, $object->canViewOnlineStatus)) {
				$object = new UserOnline($object);
				$this->objects[$object->userID] = $object;
				$this->indexToObject[] = $object->userID;
			}
		}
		$this->objectIDs = $this->indexToObject;
		$this->rewind();
	}
	
	/**
	 * Gets users online stats.
	 */
	public function readStats() {
		$sql = "SELECT		user_option_value.userOption".User::getUserOptionID('canViewOnlineStatus')." AS canViewOnlineStatus, session.userID
			FROM		wcf".WCF_N."_session session
			LEFT JOIN	wcf".WCF_N."_user_option_value user_option_value
			ON		(user_option_value.userID = session.userID)
			".$this->getConditionBuilder();
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($this->getConditionBuilder()->getParameters());
		while ($row = $statement->fetchArray()) {
			$this->stats['total']++;
			if ($row['userID']) {
				$this->stats['members']++;
				
				if ($row['canViewOnlineStatus'] && !self::isVisible($row['userID'], $row['canViewOnlineStatus'])) {
					$this->stats['invisible']++;
				}
			}
			else {
				$this->stats['guests']++;
			}
		}
	}
	
	/**
	 * Checks the 'canViewOnlineStatus' setting.
	 * 
	 * @param	integer		$userID
	 * @param	integer		$canViewOnlineStatus
	 * @return	boolean
	 */
	public static function isVisible($userID, $canViewOnlineStatus) {
		if (WCF::getSession()->getPermission('admin.user.canViewInvisible') || $userID == WCF::getUser()->userID) return true;
		
		switch ($canViewOnlineStatus) {
			case 0: // everyone
				return true;
			case 1: // registered
				if (WCF::getUser()->userID) return true;
				break;
			case 2: // following
				if (WCF::getUserProfileHandler()->isFollower($userID)) return true;
				break;
		}
		
		return false;
	}
}
