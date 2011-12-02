<?php
namespace wcf\data\user\activity\event;
use wcf\data\DatabaseObjectList;

/**
 * Represents a list of user activity events.
 * 
 * @author 	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.activity.event
 * @category 	Community Framework
 */
class UserActivityEventList extends DatabaseObjectList {
	/**
	 * @see	wcf\data\DatabaseObjectList::$className
	 */
	public $className = 'wcf\data\user\activity\event\UserActivityEvent';
}
