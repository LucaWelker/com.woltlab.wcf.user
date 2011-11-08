<?php
namespace wcf\system\user\option;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\system\style\StyleHandler;
use wcf\system\WCF;

/**
 * UserOptionOutputGender is an implementation of IUserOptionOutput for the output the gender option.
 *
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.option
 * @category 	Community Framework
 */
class UserOptionOutputGender extends UserOptionOutputSelectOptions {
	/**
	 * @see wcf\system\user\option\IUserOptionOutput::getShortOutput()
	 */
	public function getShortOutput(User $user, UserOption $option, $value) {
		if ($value == 1) {
			$title = WCF::getLanguage()->getDynamicVariable('wcf.user.profile.gender.male', array('username' => $user->username));
			return '<img src="'.StyleManager::getInstance()->getStyle()->getIconPath('genderMale', 'S').'" alt="'.$title.'" title="'.$title.'" />';
		}
		else if ($value == 2) {
			$title = WCF::getLanguage()->getDynamicVariable('wcf.user.profile.gender.female', array('username' => $user->username));
			return '<img src="'.StyleManager::getInstance()->getStyle()->getIconPath('genderFemale', 'S').'" alt="'.$title.'" title="'.$title.'" />';
		}
		else {
			return '';
		}
	}
}
?>
