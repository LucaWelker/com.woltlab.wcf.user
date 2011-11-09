<?php
namespace wcf\system\menu\user\profile\content;
use wcf\system\SingletonFactory;

/**
 * Handles user profile overview content.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.menu.user.profile.content
 * @category 	Community Framework
 */
class OverviewUserProfileMenuContent extends SingletonFactory implements IUserProfileMenuContent {
	/**
	 * @see	wcf\system\menu\user\profile\content\IUserProfileMenuContent::getContent()
	 */
	public function getContent($userID) {
		return 'IMPLEMENT ME: '.get_class($this);
	}
}
