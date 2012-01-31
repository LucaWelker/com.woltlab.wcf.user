<?php
namespace wcf\system\option\user;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\data\user\UserProfile;
use wcf\system\style\StyleHandler;
use wcf\system\WCF;

/**
 * UserOptionOutputGender is an implementation of IUserOptionOutput for the output the gender option.
 *
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.option.user
 * @category 	Community Framework
 */
class UserOptionOutputGender extends UserOptionOutputSelectOptions {
	/**
	 * @see wcf\system\option\user\IUserOptionOutput::getShortOutput()
	 */
	public function getShortOutput(User $user, UserOption $option, $value) {
		if ($value == UserProfile::GENDER_MALE) {
			$title = WCF::getLanguage()->getDynamicVariable('wcf.user.profile.gender.male', array('username' => $user->username));
			return '<img src="'.StyleHandler::getInstance()->getStyle()->getIconPath('genderMale', 'S').'" alt="'.$title.'" title="'.$title.'" />';
		}
		else if ($value == UserProfile::GENDER_FEMALE) {
			$title = WCF::getLanguage()->getDynamicVariable('wcf.user.profile.gender.female', array('username' => $user->username));
			return '<img src="'.StyleHandler::getInstance()->getStyle()->getIconPath('genderFemale', 'S').'" alt="'.$title.'" title="'.$title.'" />';
		}
		else {
			return '';
		}
	}
}
?>
