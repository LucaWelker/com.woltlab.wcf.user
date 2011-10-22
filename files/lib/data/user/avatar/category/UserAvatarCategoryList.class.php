<?php
namespace wcf\data\user\avatar\category;
use wcf\data\DatabaseObjectList;

/**
 * Represents a list of avatar categories.
 * 
 * @author 	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.avatar.category
 * @category 	Community Framework
 */
class UserAvatarCategoryList extends DatabaseObjectList {
	/**
	 * @see	wcf\data\DatabaseObjectList::$className
	 */
	public $className = 'wcf\data\user\avatar\category\UserAvatarCategory';
}
