<?php
namespace wcf\data\user\rank;
use wcf\data\DatabaseObject;

/**
 * Represents a user rank.
 *
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.rank
 * @category	Community Framework
 */
class UserRank extends DatabaseObject {
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'user_rank';
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableIndexName
	 */
	protected static $databaseTableIndexName = 'rankID';
}
