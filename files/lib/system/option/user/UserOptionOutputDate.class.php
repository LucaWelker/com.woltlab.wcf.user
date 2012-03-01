<?php
namespace wcf\system\option\user;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\util\DateUtil;

/**
 * UserOptionOutputDate is an implementation of IUserOptionOutput for the output of a date input.
 *
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.option.user
 * @category 	Community Framework
 */
class UserOptionOutputDate implements IUserOptionOutput {
	/**
	 * @see wcf\system\option\user\IUserOptionOutput::getShortOutput()
	 */
	public function getShortOutput(User $user, UserOption $option, $value) {
		return $this->getOutput($user, $option, $value);
	}
	
	/**
	 * @see wcf\system\option\user\IUserOptionOutput::getMediumOutput()
	 */
	public function getMediumOutput(User $user, UserOption $option, $value) {
		return $this->getOutput($user, $option, $value);
	}

	/**
	 * @see wcf\system\option\user\IUserOptionOutput::getOutput()
	 */
	public function getOutput(User $user, UserOption $option, $value) {
		if (empty($value) || $value == '0000-00-00') return '';
		
		$date = self::splitDate($value);
		return DateUtil::format(DateUtil::getDateTimeByTimestamp(gmmktime(12, 1, 1, $date['month'], $date['day'], $date['year'])));
	}
	
	protected static function splitDate($value) {
		$year = $month = $day = 0;
		$optionValue = explode('-', $value);
		if (isset($optionValue[0])) $year = intval($optionValue[0]);
		if (isset($optionValue[1])) $month = intval($optionValue[1]);
		if (isset($optionValue[2])) $day = intval($optionValue[2]);
		
		return array('year' => $year, 'month' => $month, 'day' => $day);
	}
}
