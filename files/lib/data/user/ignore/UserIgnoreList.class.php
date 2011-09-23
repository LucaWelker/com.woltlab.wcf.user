<?php
namespace wcf\data\user\ignore;
use wcf\data\DatabaseObjectList;

/**
 * Represents a list of ignored users.
 * 
 * @author 	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
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
}
