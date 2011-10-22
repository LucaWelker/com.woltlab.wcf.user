<?php
namespace wcf\data\user\avatar\category;
use wcf\data\AbstractDatabaseObjectAction;

/**
 * Executes avatar category-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.avatar.category
 * @category 	Community Framework
 */
class UserAvatarCategoryAction extends AbstractDatabaseObjectAction {
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$className
	 */
	public $className = 'wcf\data\user\avatar\category\UserAvatarCategoryEditor';
}
