<?php
namespace wcf\form;
use wcf\data\user\User;
use wcf\data\user\UserEditor;
use wcf\system\exception\UserInputException;
use wcf\system\mail\Mail;
use wcf\system\WCF;
use wcf\util\StringUtil;
use wcf\util\UserRegistrationUtil;
use wcf\util\UserUtil;

/**
 * Shows the new activation code form.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	form
 * @category 	Community Framework
 */
class RegisterNewActivationCodeForm extends AbstractForm {
	// system
	public $templateName = 'registerNewActivationCode';
	
	/**
	 * username
	 * @var	string
	 */
	public $username = '';
	
	/**
	 * password
	 * @var	string
	 */
	public $password = '';
	
	/**
	 * email
	 * @var	string
	 */
	public $email = '';
	
	/**
	 * user object
	 * @var	wcf\data\user\User
	 */
	public $user = null;
	
	/**
	 * @see wcf\form\Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['username'])) $this->username = StringUtil::trim($_POST['username']);
		if (isset($_POST['password'])) $this->password = $_POST['password'];
		if (isset($_POST['email'])) $this->email = StringUtil::trim($_POST['email']);
	}
	
	/**
	 * @see wcf\form\Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		// username
		$this->validateUsername();
		
		// password
		$this->validatePassword();
		
		// email
		$this->validateEmail();
	}
	
	/**
	 * Validates the username.
	 */
	public function validateUsername() {
		if (empty($this->username)) {
			throw new UserInputException('username');
		}
		
		$this->user = User::getUserByUsername($this->username);
		if (!$this->user->userID) {
			throw new UserInputException('username', 'notFound');
		}
		
		if ($this->user->activationCode == 0) {
			throw new UserInputException('username', 'alreadyEnabled');
		}
	}
	
	/**
	 * Validates the password.
	 */
	public function validatePassword() {
		if (empty($this->password)) {
			throw new UserInputException('password');
		}
		
		// check password
		if (!$this->user->checkPassword($this->password)) {
			throw new UserInputException('password', 'false');
		}
	}
	
	/**
	 * Validates the email address.
	 */
	public function validateEmail() {
		if (!empty($this->email)) {
			if (!UserRegistrationUtil::isValidEmail($this->email)) {
				throw new UserInputException('email', 'notValid');
			}
			
			// Check if email exists already.
			if (!UserUtil::isAvailableEmail($this->email)) {
				throw new UserInputException('email', 'notUnique');
			}
		}
	}
	
	
	/**
	 * @see wcf\form\Form::save()
	 */
	public function save() {
		parent::save();
		
		// generate activation code
		$activationCode = UserRegistrationUtil::getActivationCode();
		
		// save user
		$userEditor = new UserEditor($this->user);
		$parameters = array('activationCode' => $activationCode);
		if (!empty($this->email)) $parameters['email'] = $this->email;
		$userEditor->update($parameters);
		
		// send activation mail
		$subjectData = array('PAGE_TITLE' => WCF::getLanguage()->get(PAGE_TITLE));
		$messageData = array(
			'PAGE_TITLE' => WCF::getLanguage()->get(PAGE_TITLE),
			'PAGE_URL' => PAGE_URL,
			'MAIL_ADMIN_ADDRESS' => MAIL_ADMIN_ADDRESS,
			'username' => $this->user->username,
			'userID' => $this->user->userID,
			'activationCode' => $activationCode
		);
		$mail = new Mail(	array($this->user->username => (!empty($this->email) ? $this->email : $this->user->email)),
					WCF::getLanguage()->getDynamicVariable('wcf.user.register.needActivation.mail.subject', $subjectData),
					WCF::getLanguage()->getDynamicVariable('wcf.user.register.needActivation.mail', $messageData));
		$mail->send();
		$this->saved();
		
		// forward to index page
		WCF::getTPL()->assign(array(
			'url' => 'index.php'.SID_ARG_1ST,
			'message' => WCF::getLanguage()->get('wcf.user.register.newActivationCode.success', array('$email' => (!empty($this->email) ? $this->email : $this->user->email)))
		));
		WCF::getTPL()->display('redirect');
		exit;
	}
	
	/**
	 * @see wcf\page\Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST) && WCF::getUser()->userID) {
			$this->username = WCF::getUser()->username;
		}
	}
	
	/**
	 * @see wcf\page\Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'username' => $this->username,
			'password' => $this->password,
			'email' => $this->email
		));
	}
}
?>
