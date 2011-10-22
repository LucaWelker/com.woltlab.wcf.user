<?php
namespace wcf\data\user\avatar;
use wcf\data\DatabaseObjectList;

/**
 * Represents a list of avatars.
 * 
 * @author 	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.avatar
 * @category 	Community Framework
 */
class UserAvatarList extends DatabaseObjectList {
	/**
	 * @see	wcf\data\DatabaseObjectList::$className
	 */
	public $className = 'wcf\data\user\avatar\UserAvatar';
}
