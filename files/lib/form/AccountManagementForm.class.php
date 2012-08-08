<?php
namespace wcf\form;
use wcf\data\user\UserEditor;
use wcf\system\exception\UserInputException;
use wcf\system\mail\Mail;
use wcf\system\menu\user\UserMenu;
use wcf\system\WCF;
use wcf\util\HeaderUtil;
use wcf\util\StringUtil;
use wcf\util\UserRegistrationUtil;
use wcf\util\UserUtil;

/**
 * Shows the account management form.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	form
 * @category	Community Framework
 */
class AccountManagementForm extends AbstractSecureForm {
	/**
	 * @see wcf\page\AbstractPage::$loginRequired
	 */
	public $loginRequired = true;
	
	/**
	 * user password
	 * @var string
	 */
	public $password = '';
	
	/**
	 * new email address
	 * @var string
	 */
	public $email = '';
	
	/**
	 * confirmed new email address
	 * @var string
	 */
	public $confirmEmail = '';
	
	/**
	 * new password
	 * @var string
	 */
	public $newPassword = '';
	
	/**
	 * confirmed new password
	 * @var string
	 */
	public $confirmNewPassword = '';
	
	/**
	 * new user name
	 * @var string
	 */
	public $username = '';
	
	// quit
	public $quit = 0;
	public $cancelQuit = 0;
	public $quitStarted = 0;
	
	/**
	 * @see wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		$this->quitStarted = WCF::getUser()->quitStarted;
	}
	
	/**
	 * @see wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['password'])) $this->password = $_POST['password'];
		if (isset($_POST['email'])) $this->email = $_POST['email'];
		if (isset($_POST['confirmEmail'])) $this->confirmEmail = $_POST['confirmEmail'];
		if (isset($_POST['newPassword'])) $this->newPassword = $_POST['newPassword'];
		if (isset($_POST['confirmNewPassword'])) $this->confirmNewPassword = $_POST['confirmNewPassword'];
		if (isset($_POST['username'])) $this->username = StringUtil::trim($_POST['username']);
		if (isset($_POST['quit'])) $this->quit = intval($_POST['quit']);
		if (isset($_POST['cancelQuit'])) $this->cancelQuit = intval($_POST['cancelQuit']);
	}
	
	/**
	 * @see wcf\form\IForm::validate()
	 */
	public function validate() {
		parent::validate();
		
		// password
		if (empty($this->password)) {
			throw new UserInputException('password');
		}
		
		if (!WCF::getUser()->checkPassword($this->password)) {
			throw new UserInputException('password', 'false');
		}
		
		// user name
		if (WCF::getSession()->getPermission('user.profile.canRename') && $this->username != WCF::getUser()->username) {
			if (StringUtil::toLowerCase($this->username) != StringUtil::toLowerCase(WCF::getUser()->username)) {
				if (WCF::getUser()->lastUsernameChange + WCF::getSession()->getPermission('user.profile.renamePeriod') * 86400 > TIME_NOW) {
					throw new UserInputException('username', 'alreadyRenamed');
				}
				
				// checks for forbidden chars (e.g. the ",")
				if (!UserRegistrationUtil::isValidUsername($this->username)) {
					throw new UserInputException('username', 'notValid');
				}
				
				// checks if user name exists already.
				if (!UserUtil::isAvailableUsername($this->username)) {
					throw new UserInputException('username', 'notUnique');
				}
			}
		}
		// password
		if (!empty($this->newPassword) || !empty($this->confirmNewPassword)) {
			if (empty($this->newPassword)) {
				throw new UserInputException('newPassword');
			}
			
			if (empty($this->confirmNewPassword)) {
				throw new UserInputException('confirmNewPassword');
			}
			
			if (!UserRegistrationUtil::isSecurePassword($this->newPassword)) {
				throw new UserInputException('newPassword', 'notSecure');
			}
			
			if ($this->newPassword != $this->confirmNewPassword) {
				throw new UserInputException('confirmNewPassword', 'notEqual');
			}
		}
		// email
		if (WCF::getSession()->getPermission('user.profile.canChangeEmail') && $this->email != WCF::getUser()->email && $this->email != WCF::getUser()->newEmail) {
			if (empty($this->email)) {	
				throw new UserInputException('email');
			}
		
			// checks if only letter case has changed
			if (StringUtil::toLowerCase($this->email) != StringUtil::toLowerCase(WCF::getUser()->email)) {
				// check for valid email (one @ etc.)
				if (!UserRegistrationUtil::isValidEmail($this->email)) {
					throw new UserInputException('email', 'notValid');
				}
				
				// checks if email already exists.
				if (!UserUtil::isAvailableEmail($this->email)) {
					throw new UserInputException('email', 'notUnique');
				}
			}
			
			// checks confirm input
			if (StringUtil::toLowerCase($this->email) != StringUtil::toLowerCase($this->confirmEmail)) {
				throw new UserInputException('confirmEmail', 'notEqual');
			}
		}
	}
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		// default values
		if (!count($_POST)) {
			$this->username = WCF::getUser()->username;
			$this->email = $this->confirmEmail = WCF::getUser()->email;
		}
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'password' => $this->password,
			'email' => $this->email,
			'confirmEmail' => $this->confirmEmail,
			'newPassword' => $this->newPassword,
			'confirmNewPassword' => $this->confirmNewPassword,
			'username' => $this->username,
			'renamePeriod' => WCF::getSession()->getPermission('user.profile.renamePeriod'),
			'quitStarted' => $this->quitStarted,
			'quit' => $this->quit,
			'cancelQuit' => $this->cancelQuit
		));
	}
	
	/**
	 * @see wcf\page\IPage::show()
	 */
	public function show() {
		// set active tab
		UserMenu::getInstance()->setActiveMenuItem('wcf.user.menu.profile.accountManagement');
		
		parent::show();
	}
	
	/**
	 * @see wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
		
		$success = array();
		$updateParameters = array();
		$userEditor = new UserEditor(WCF::getUser());
		
		// quit
		if (WCF::getSession()->getPermission('user.profile.canQuit')) {
			if (!WCF::getUser()->quitStarted && $this->quit == 1) {
				$updateParameters['quitStarted'] = TIME_NOW;
				$this->quitStarted = TIME_NOW;
				$success[] = 'wcf.user.quit.success';
			}
			else if (WCF::getUser()->quitStarted && $this->cancelQuit == 1) {
				$updateParameters['quitStarted'] = 0;
				$this->quitStarted = 0;
				$success[] = 'wcf.user.quit.cancel.success';
			}
		}
		
		// user name
		if (WCF::getSession()->getPermission('user.profile.canRename') && $this->username != WCF::getUser()->username) {
			if (StringUtil::toLowerCase($this->username) != StringUtil::toLowerCase(WCF::getUser()->username)) {
				$updateParameters['lastUsernameChange'] = TIME_NOW;
				$updateParameters['oldUsername'] = $userEditor->username;
			}
			$updateParameters['username'] = $this->username;
			$success[] = 'wcf.user.changeUsername.success';
		}
		
		// email
		if (WCF::getSession()->getPermission('user.profile.canChangeEmail') && $this->email != WCF::getUser()->email && $this->email != WCF::getUser()->newEmail) {
			if (REGISTER_ACTIVATION_METHOD == 0 || REGISTER_ACTIVATION_METHOD == 2 || StringUtil::toLowerCase($this->email) == StringUtil::toLowerCase(WCF::getUser()->email)) {
				// update email
				$updateParameters['email'] = $this->email;
				$success[] = 'wcf.user.changeEmail.success';
			}
			else if (REGISTER_ACTIVATION_METHOD == 1) {
				// get reactivation code
				$activationCode = UserRegistrationUtil::getActivationCode();
				
				// save as new email
				$updateParameters['reactivationCode'] = $activationCode;
				$updateParameters['newEmail'] = $this->email;
				
				$messageData = array(
					'username' => WCF::getUser()->username,
					'userID' => WCF::getUser()->userID,
					'activationCode' => $activationCode
				);
				
				$mail = new Mail(array(WCF::getUser()->username => $this->email), WCF::getLanguage()->getDynamicVariable('wcf.user.changeEmail.needReactivation.mail.subject'), WCF::getLanguage()->getDynamicVariable('wcf.user.changeEmail.needReactivation.mail', $messageData));
				$mail->send();
				$success[] = 'wcf.user.changeEmail.needReactivation';
			}
		}
		
		// password
		if (!empty($this->newPassword) || !empty($this->confirmNewPassword)) {
			$userEditor->update(array(
				'password' => $this->newPassword
			));
			
			// update cookie
			if (isset($_COOKIE[COOKIE_PREFIX.'password'])) {
				HeaderUtil::setCookie('password', StringUtil::getSaltedHash($this->newPassword, $userEditor->salt), TIME_NOW + 365 * 24 * 3600);
			}
			
			$success[] = 'wcf.user.changePassword.success';
		}
		
		if (!empty($updateParameters)) {
			$userEditor->update($updateParameters);
		}
		
		$this->saved();
		
		// show success message
		WCF::getTPL()->assign('success', $success);
		
		// reset password
		$this->password = '';
		$this->newPassword = $this->confirmNewPassword = '';
	}
}
