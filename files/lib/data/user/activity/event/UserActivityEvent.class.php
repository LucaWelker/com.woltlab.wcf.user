<?php
namespace wcf\data\user\activity\event;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 * Represents a user's activity.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.activity.event
 * @category 	Community Framework
 */
class UserActivityEvent extends DatabaseObject {
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'user_activity_event';
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableIndexName
	 */
	protected static $databaseTableIndexName = 'eventID';
}
