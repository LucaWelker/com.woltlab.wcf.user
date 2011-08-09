<?php
namespace wcf\data\user;
use wcf\system\exception\ValidateActionException;
use wcf\util\StringUtil;
use wcf\util\UserUtil;
use wcf\util\UserRegistrationUtil;

/**
 * Executes user-related actions.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user
 * @category 	Community Framework
 */
class UserRegistrationAction extends UserAction {
	/**
	 * Validates the validate username function.
	 */
	public function validateValidateUsername() {
		if (empty($this->parameters['username'])) {
			return ValidateActionException("missing parameter 'username'");
		}
	}
	
	/**
	 * Validates the validate email address function.
	 */
	public function validateValidateEmailAddress() {
		if (empty($this->parameters['email'])) {
			return ValidateActionException("missing parameter 'email'");
		}
	}
	
	/**
	 * Validates the validate password function.
	 */
	public function validateValidatePassword() {
		if (empty($this->parameters['password'])) {
			return ValidateActionException("missing parameter 'password'");
		}
	}
	
	/**
	 * Validates the given username.
	 */
	public function validateUsername() {
		$this->parameters['username'] = StringUtil::trim($this->parameters['username']);
		
		if (!UserRegistrationUtil::isValidUsername($this->parameters['username'])) {
			return array(
				'isValid' => false,
				'error' => 'notValid'
			);
		}
		
		if (!UserUtil::isAvailableUsername($this->parameters['username'])) {
			return array(
				'isValid' => false,
				'error' => 'notUnique'
			);
		}
	
		return array(
			'isValid' => true
		);
	}
	
	/**
	 * Validates given email address.
	 */
	public function validateEmailAddress() {
		$this->parameters['email'] = StringUtil::trim($this->parameters['email']);
		
		if (!UserRegistrationUtil::isValidEmail($this->parameters['email'])) {
			return array(
				'isValid' => false,
				'error' => 'notValid'
			);
		}
		
		if (!UserUtil::isAvailableEmail($this->parameters['email'])) {
			return array(
				'isValid' => false,
				'error' => 'notUnique'
			);
		}
	
		return array(
			'isValid' => true
		);
	}
	
	/**
	 * Validates given password.
	 */
	public function validatePassword() {
		if (!UserRegistrationUtil::isSecurePassword($this->parameters['password'])) {
			return array(
				'isValid' => false,
				'error' => 'notSecure'
			);
		}
		
		return array(
			'isValid' => true
		);
	}
}
