<?php
namespace wcf\data\user\avatar;
use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit avatars.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.avatar
 * @category 	Community Framework
 */
class UserAvatarEditor extends DatabaseObjectEditor {
	/**
	 * @see	wcf\data\DatabaseObjectDecorator::$baseClass
	 */
	protected static $baseClass = 'wcf\data\user\avatar\UserAvatar';
}
