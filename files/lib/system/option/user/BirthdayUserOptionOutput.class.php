<?php
namespace wcf\system\option\user;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\util\DateUtil;

/**
 * User option output implementation for the output of a user's birthday.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.option.user
 * @category	Community Framework
 */
class BirthdayUserOptionOutput extends DateUserOptionOutput {
	/**
	 * @see	wcf\system\option\user\IUserOptionOutput::getOutput()
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
