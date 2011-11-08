<?php
namespace wcf\system\user\option;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\util\StringUtil;

/**
 * UserOptionOutputFloat is an implementation of IUserOptionOutput for the output of a float.
 *
 * @author	Tobias Friebel
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.option
 * @category 	Community Framework
 */
class UserOptionOutputFloat implements IUserOptionOutput {
	/**
	 * @see wcf\system\user\option\IUserOptionOutput::getShortOutput()
	 */
	public function getShortOutput(User $user, UserOption $option, $value) {
		return $this->getOutput($user, $option, $value);
	}

	/**
	 * @see wcf\system\user\option\IUserOptionOutput::getMediumOutput()
	 */
	public function getMediumOutput(User $user, UserOption $option, $value) {
		return $this->getOutput($user, $option, $value);
	}

	/**
	 * @see wcf\system\user\option\IUserOptionOutput::getOutput()
	 */
	public function getOutput(User $user, UserOption $option, $value) {
		if (empty($value) || $value == '0') {
			$value = 0.00;
		}

		return StringUtil::formatDouble($value, 2);
	}
}
?>
