<?php
namespace wcf\system\user\option;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\system\style\StyleHandler;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * UserOptionOutputURL is an implementation of IUserOptionOutput for the output of an url.
 *
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.option
 * @category 	Community Framework
 */
class UserOptionOutputURL implements IUserOptionOutput, IUserOptionOutputContactInformation {
	/**
	 * @see wcf\system\user\option\IUserOptionOutput::getShortOutput()
	 */
	public function getShortOutput(User $user, UserOption $option, $value) {
		return $this->getImage($user, $value, 'S');
	}
	
	/**
	 * @see wcf\system\user\option\IUserOptionOutput::getMediumOutput()
	 */
	public function getMediumOutput(User $user, UserOption $option, $value) {
		return $this->getImage($user, $value);
	}
	
	/**
	 * @see wcf\system\user\option\IUserOptionOutput::getOutput()
	 */
	public function getOutput(User $user, UserOption $option, $value) {
		if (empty($value) || $value == 'http://') return '';
		
		$value = self::getURL($value);
		$value = StringUtil::encodeHTML($value);
		return '<a href="'.$value.'">'.$value.'</a>';
	}
	
	/**
	 * @see wcf\system\user\option\IUserOptionOutputContactInformation::getOutput()
	 */
	public function getOutputData(User $user, UserOption $option, $value) {
		if (empty($value) || $value == 'http://') return null;
		
		$value = self::getURL($value);
		$value = StringUtil::encodeHTML($value);
		
		return array(
			'icon' => StyleManager::getStyle()->getIconPath('website', 'M'),
			'title' => WCF::getLanguage()->get('wcf.user.option.'.$option->optionName),
			'value' => $value,
			'url' => $value
		);
	}
	
	/**
	 * Generates an image button.
	 * 
	 * @see wcf\system\user\option\IUserOptionOutput::getShortOutput()
	 */
	protected function getImage(User $user, $value, $imageSize = 'M') {
		if (empty($value) || $value == 'http://') return '';
		
		$value = self::getURL($value);
		$title = WCF::getLanguage()->getDynamicVariable('wcf.user.profile.homepage.title', array('$usernam' => StringUtil::encodeHTML($user->username)));
		return '<a href="'.StringUtil::encodeHTML($value).'"><img src="'.StyleManager::getInstance()->getStyle()->getIconPath('website', $imageSize).'" alt="" title="'.$title.'" /></a>';
	}
	
	/**
	 * Formats the URL.
	 * 
	 * @param	string		$url
	 * @return	string
	 */
	private static function getURL($url) {
		if (!preg_match('~^https?://~i', $url)) {
			$url = 'http://'.$url;
		}
		
		return $url;
	}
}
?>
