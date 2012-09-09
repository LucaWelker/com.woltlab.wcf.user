<?php
namespace wcf\system\option\user;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\system\style\StyleHandler;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * SelectOptions for the output of an url.
 *
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.option.user
 * @category 	Community Framework
 */
class URLUserOptionOutput implements IUserOptionOutput, IUserOptionOutputContactInformation {
	/**
	 * @see	wcf\system\option\user\IUserOptionOutput::getShortOutput()
	 */
	public function getShortOutput(User $user, UserOption $option, $value) {
		return $this->getImage($user, $value, 'S');
	}
	
	/**
	 * @see	wcf\system\option\user\IUserOptionOutput::getMediumOutput()
	 */
	public function getMediumOutput(User $user, UserOption $option, $value) {
		return $this->getImage($user, $value);
	}
	
	/**
	 * @see	wcf\system\option\user\IUserOptionOutput::getOutput()
	 */
	public function getOutput(User $user, UserOption $option, $value) {
		if (empty($value) || $value == 'http://') return '';
		
		$value = self::getURL($value);
		$value = StringUtil::encodeHTML($value);
		return '<a href="'.$value.'">'.$value.'</a>';
	}
	
	/**
	 * @see	wcf\system\option\user\IUserOptionOutputContactInformation::getOutput()
	 */
	public function getOutputData(User $user, UserOption $option, $value) {
		if (empty($value) || $value == 'http://') return null;
		
		$value = self::getURL($value);
		$value = StringUtil::encodeHTML($value);
		
		return array(
			'icon' => StyleHandler::getInstance()->getStyle()->getIconPath('globe', 'M'),
			'title' => WCF::getLanguage()->get('wcf.user.option.'.$option->optionName),
			'value' => $value,
			'url' => $value
		);
	}
	
	/**
	 * Generates an image button.
	 * 
	 * @see	wcf\system\option\user\IUserOptionOutput::getShortOutput()
	 */
	protected function getImage(User $user, $value, $imageSize = 'M') {
		if (empty($value) || $value == 'http://') return '';
		
		$value = self::getURL($value);
		$title = WCF::getLanguage()->getDynamicVariable('wcf.user.profile.homepage.title', array('username' => StringUtil::encodeHTML($user->username)));
		return '<a href="'.StringUtil::encodeHTML($value).'"><img src="'.StyleHandler::getInstance()->getStyle()->getIconPath('globe', $imageSize).'" alt="" title="'.$title.'" /></a>';
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
