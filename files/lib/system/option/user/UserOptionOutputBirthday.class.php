<?php
namespace wcf\system\option\user;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\util\DateUtil;

/**
 * UserOptionOutputBirthday is an implementation of IUserOptionOutput for the output of a date input.
 *
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.option.user
 * @category 	Community Framework
 */
class UserOptionOutputBirthday extends UserOptionOutputDate {
	/**
	 * @see wcf\system\option\user\IUserOptionOutput::getOutput()
	 */
	public function getOutput(User $user, UserOption $option, $value) {
		$dateString = parent::getOutput($user, $option, $value);
		if ($dateString) {
			// TODO: add option to hide year/age
			if ($age = DateUtil::getAge($value)) {
				$dateString .= ' ('.$age.')';
			}
		}
		
		return $dateString;
	}
}
