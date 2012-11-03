<?php
namespace wcf\system\option\user;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\system\request\LinkHandler;
use wcf\system\style\StyleHandler;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * User option outpur implementation for the output of a user's email address.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.option.user
 * @category	Community Framework
 */
class EmailUserOptionOutput implements IUserOptionOutput, IUserOptionOutputContactInformation {
	/**
	 * @see	wcf\system\option\user\IUserOptionOutput::getShortOutput()
	 */
	public function getShortOutput(User $user, UserOption $option, $value) {
		return $this->getImage($user, 'S');
	}
	
	/**
	 * @see	wcf\system\option\user\IUserOptionOutput::getMediumOutput()
	 */
	public function getMediumOutput(User $user, UserOption $option, $value) {
		return $this->getImage($user);
	}
	
	/**
	 * @see	wcf\system\option\user\IUserOptionOutput::getOutput()
	 */
	public function getOutput(User $user, UserOption $option, $value) {
		if (!$user->email) return '';
		if ($user->hideEmailAddress && !WCF::getSession()->getPermission('admin.user.canMailUser')) return '';
		if (!WCF::getSession()->getPermission('user.mail.canMail')) return '';
		$email = StringUtil::encodeAllChars($user->email);
		return '<a href="mailto:'.$email.'">'.$email.'</a>';
	}
	
	/**
	 * @see	wcf\system\option\user\IUserOptionOutputContactInformation::getOutput()
	 */
	public function getOutputData(User $user, UserOption $option, $value) {
		if (!$user->email) return null;
		if (!$user->hideEmailAddress || WCF::getSession()->getPermission('admin.user.canMailUser')) {
			$email = StringUtil::encodeAllChars($user->email);
			return array(
				'icon' => StyleHandler::getInstance()->getStyle()->getIconPath('email', 'M'),
				'title' => WCF::getLanguage()->get('wcf.user.option.'.$option->optionName),
				'value' => $email,
				'url' => 'mailto:'.$email
			);
		}
		else if ($user->userCanMail && WCF::getSession()->getPermission('user.mail.canMail')) {
			return array(
				'icon' => StyleHandler::getInstance()->getStyle()->getIconPath('email', 'M'),
				'title' => WCF::getLanguage()->get('wcf.user.option.'.$option->optionName),
				'value' => WCF::getLanguage()->getDynamicVariable('wcf.user.profile.email.title', array('username' => StringUtil::encodeHTML($user->username))),
				'url' => StringUtil::encodeHTML(LinkHandler::getInstance()->getLink('Mail', array('id' => $user->userID)))
			);
		}
		else {
			return null;
		}
	}
	
	/**
	 * Generates an image button.
	 * 
	 * @see	wcf\system\option\user\IUserOptionOutput::getShortOutput()
	 */
	protected function getImage(User $user, $imageSize = 'M') {
		if (!$user->email) return '';
		if (!$user->hideEmailAddress || WCF::getSession()->getPermission('admin.user.canMailUser')) {
			$url = 'mailto:'.StringUtil::encodeAllChars($user->email);
		}
		else if ($user->userCanMail && WCF::getSession()->getPermission('user.mail.canMail')) {
			$url = StringUtil::encodeHTML(LinkHandler::getInstance()->getLink('Mail', array('id' => $user->userID)));
		}
		else {
			return '';
		}
		
		$title = WCF::getLanguage()->getDynamicVariable('wcf.user.profile.email.title', array('username' => StringUtil::encodeHTML($user->username)));
		return '<a href="'.$url.'"><img src="'.StyleHandler::getInstance()->getStyle()->getIconPath('email', $imageSize).'" alt="" title="'.$title.'" /></a>';
	}
}

