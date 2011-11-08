<?php
namespace wcf\system\user\option;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\system\WCF;
use wcf\util\OptionUtil;
use wcf\util\StringUtil;

/**
 * UserOptionOutputSelectOptions is an implementation of IUserOptionOutput for the output of a date input.
 *
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.option
 * @category 	Community Framework
 */
class UserOptionOutputSelectOptions implements IUserOptionOutput {
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
		$result = self::getResult($option, $value);
		if ($result === null) {
			return '';
		}
		else if (is_array($result)) {
			$output = '';
			foreach ($result as $resultValue) {
				if (!empty($output)) $output .= "<br />\n";
				$output .= WCF::getLanguage()->get($resultValue);
			}
			
			return $output;
		}
		else {
			return WCF::getLanguage()->get($result);
		}
	}
	
	protected static function getResult(UserOption $option, $value) {
		$options = OptionUtil::parseSelectOptions($option->selectOptions);
		
		// multiselect
		if (StringUtil::indexOf($value, "\n") !== false) {
			$values = explode("\n", $value);
			$result = array();
			foreach ($values as $value) {
				if (isset($options[$value])) {
					$result[] = $options[$value];
				}
			}
			
			return $result;
		}
		else {
			if (!empty($value) && isset($options[$value])) return $options[$value];
			return null;
		}
	}
}
?>
