<?php
namespace wcf\data\user\friend\request;
use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit friend requests.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.friend.request
 * @category 	Community Framework
 */
class UserFriendRequestEditor extends DatabaseObjectEditor {
	/**
	 * @see	wcf\data\DatabaseObjectDecorator::$baseClass
	 */
	protected static $baseClass = 'wcf\data\user\friend\request\UserFriendRequest';
}
