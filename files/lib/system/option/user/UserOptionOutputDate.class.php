<?php
namespace wcf\system\option\user;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\system\style\StyleHandler;
use wcf\system\WCF;
use wcf\util\DateUtil;
use wcf\util\StringUtil;

/**
 * UserOptionOutputDate is an implementation of IUserOptionOutput for the output of a date input.
 *
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
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
		if ($option->optionType == 'birthday') {
			// show cake icon
			if (empty($value) || $value == '0000-00-00') return '';
			
			$age = 0;
			$date = self::splitDate($value);
			if ($date['year']) $age = self::calcAge($date['year'], $date['month'], $date['day']);
			
			if ($date['month'] == intval(DateUtil::formatDate('%m', null, false, true)) && $date['day'] == DateUtil::formatDate('%e', null, false, true)) {
				WCF::getTPL()->assign(array(
					'age' => $age,
					'username' => $user->username
				));
				return '<img src="'.StyleHandler::getInstance()->getStyle()->getIconPath('birthday', 'S').'" alt="'.WCF::getLanguage()->getDynamicVariable('wcf.user.profile.birthday').'" title="'.WCF::getLanguage()->getDynamicVariable('wcf.user.profile.birthday').'" />';
			}
		}
		else {
			return $this->getOutput($user, $option, $value);
		}
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
		
		$age = 0;
		$date = self::splitDate($value);
		
		// format date
		$dateString = DateUtil::formatDate(null, gmmktime(12, 1, 1, $date['month'], $date['day'], ($date['year'] ? $date['year'] : 2028)));
		if (!$date['year']) $dateString = StringUtil::replace('2028', '', $dateString);
		
		// calc age
		if ($date['year'] && $option->optionType == 'birthday') {
			$age = self::calcAge($date['year'], $date['month'], $date['day']);
		}
		
		return $dateString . ($age ? ' ('.$age.')' : '');
	}
	
	protected static function splitDate($value) {
		$year = $month = $day = 0;
		$optionValue = explode('-', $value);
		if (isset($optionValue[0])) $year = intval($optionValue[0]);
		if (isset($optionValue[1])) $month = intval($optionValue[1]);
		if (isset($optionValue[2])) $day = intval($optionValue[2]);
		
		return array('year' => $year, 'month' => $month, 'day' => $day);
	}
	
	protected static function calcAge($year, $month, $day) {
		$age = DateUtil::formatDate('%Y', null, false, true) - $year;
		if (intval(DateUtil::formatDate('%m', null, false, true)) < intval($month)) $age--;
		else if (intval(DateUtil::formatDate('%m', null, false, true)) == intval($month) && DateUtil::formatDate('%e', null, false, true) < intval($day)) $age--;
		
		return $age;
	}
}
?>
