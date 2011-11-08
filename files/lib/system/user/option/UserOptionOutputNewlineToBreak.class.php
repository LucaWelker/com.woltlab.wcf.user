<?php
namespace wcf\system\user\option;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\util\StringUtil;

/**
 * UserOptionOutputNewlineToBreak is an implementation of IUserOptionOutput for a formatted textarea value.
 *
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.option
 * @category 	Community Framework
 */
class UserOptionOutputNewlineToBreak implements IUserOptionOutput {
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
		return nl2br(StringUtil::encodeHTML($value));
	}
}
?>
