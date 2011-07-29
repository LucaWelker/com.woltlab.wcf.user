<?php
namespace wcf\data\user\friend;
use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit friends.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.friend
 * @category 	Community Framework
 */
class UserFriendEditor extends DatabaseObjectEditor {
	/**
	 * @see	wcf\data\DatabaseObjectDecorator::$baseClass
	 */
	protected static $baseClass = 'wcf\data\user\friend\UserFriend';
}
