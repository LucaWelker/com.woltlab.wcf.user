<?php
namespace wcf\form;
use wcf\acp\form\UserAddForm;
use wcf\data\user\group\UserGroup;
use wcf\data\user\UserAction;
use wcf\data\user\UserEditor;
use wcf\data\user\UserProfileAction;
use wcf\system\exception\NamedUserException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\UserInputException;
use wcf\system\language\LanguageFactory;
use wcf\system\mail\Mail;
use wcf\system\recaptcha\RecaptchaHandler;
use wcf\system\request\LinkHandler;
use wcf\system\user\authentication\UserAuthenticationFactory;
use wcf\system\WCF;
use wcf\util\HeaderUtil;
use wcf\util\StringUtil;
use wcf\util\UserRegistrationUtil;

/**
 * Shows the user registration form.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	form
 * @category	Community Framework
 */
class RegisterForm extends UserAddForm {
	/**
	 * @see	wcf\page\AbstractPage::$enableTracking
	 */
	public $enableTracking = true;
	
	/**
	 * @see	wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array();
	
	/**
	 * holds a language variable with information about the registration process
	 * e.g. if you need to activate your account
	 * @var	string
	 */
	public $message = '';
	
	/**
	 * recaptcha challenge
	 * @var	string
	 */
	public $challenge = '';
	
	/**
	 * recaptcha response
	 * @var	string
	 */
	public $response = '';
	
	/**
	 * enable recaptcha
	 * @var	boolean
	 */
	public $useCaptcha = true;
	
	/**
	 * min number of seconds between form request and submit
	 * @var integer
	 */
	public static $minRegistrationTime = 15;
	
	/**
	 * @see	wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// user is already registered
		if (WCF::getUser()->userID) {
			throw new PermissionDeniedException();
		}
		
		// registration disabled
		if (REGISTER_DISABLED) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.user.register.error.disabled'));
		}
		
		// check disclaimer
		if (REGISTER_ENABLE_DISCLAIMER && !WCF::getSession()->getVar('disclaimerAccepted')) {
			HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Disclaimer'));
			exit;
		}
		
		if (!REGISTER_USE_CAPTCHA || WCF::getSession()->getVar('recaptchaDone')) {
			$this->useCaptcha = false;
		}
	}
	
	/**
	 * @see	wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		$this->groupIDs = array();
		if (isset($_POST['recaptcha_challenge_field'])) $this->challenge = StringUtil::trim($_POST['recaptcha_challenge_field']);
		if (isset($_POST['recaptcha_response_field'])) $this->response = StringUtil::trim($_POST['recaptcha_response_field']);
	}
	
	/**
	 * @see	wcf\form\IForm::validate()
	 */
	public function validate() {
		// validate captcha first
		if ($this->useCaptcha) {
			$this->validateCaptcha();
		}
		
		
		parent::validate();
		
		// validate registration time
		if (!WCF::getSession()->getVar('registrationStartTime') || (TIME_NOW - WCF::getSession()->getVar('registrationStartTime')) < self::$minRegistrationTime) {
			throw new UserInputException('registrationStartTime', array());
		}
	}
	
	/**
	 * @see	wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (empty($_POST)) {
			$this->languageID = WCF::getLanguage()->languageID;
			
			if (WCF::getSession()->getVar('__username')) {
				$this->username = WCF::getSession()->getVar('__username');
				WCF::getSession()->unregister('__username');
			}
			if (WCF::getSession()->getVar('__email')) {
				$this->email = $this->confirmEmail = WCF::getSession()->getVar('__email');
				WCF::getSession()->unregister('__email');
			}
			
			WCF::getSession()->register('registrationStartTime', TIME_NOW);
		}
	}
	
	/**
	 * Reads option tree on page init.
	 */
	protected function readOptionTree() {
		$this->optionHandler->setInRegistration(true);
		$this->optionTree = $this->optionHandler->getOptionTree('profile');
	}
	
	/**
	 * @see	wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		RecaptchaHandler::getInstance()->assignVariables();
		WCF::getTPL()->assign(array(
			'useCaptcha' => $this->useCaptcha
		));
	}
	
	/**
	 * @see	wcf\page\IPage::show()
	 */
	public function show() {
		AbstractForm::show();
	}
	
	/**
	 * Validates the captcha.
	 */
	protected function validateCaptcha() {
		if ($this->useCaptcha) {
			try {
				RecaptchaHandler::getInstance()->validate($this->challenge, $this->response);
				$this->useCaptcha = false;
			}
			catch (UserInputException $e) {
				$this->errorType[$e->getField()] = $e->getType();
			}	
		}
	}
	
	/**
	 * @see	wcf\acp\form\UserAddForm::validateUsername()
	 */
	protected function validateUsername($username) {
		parent::validateUsername($username);
		
		// check for min-max length
		if (!UserRegistrationUtil::isValidUsername($username)) {
			throw new UserInputException('username', 'notValid');
		}
	}
	
	/**
	 * @see	wcf\acp\form\UserAddForm::validatePassword()
	 */
	protected function validatePassword($password, $confirmPassword) {
		parent::validatePassword($password, $confirmPassword);
		
		// check security of the given password
		if (!UserRegistrationUtil::isSecurePassword($password)) {
			throw new UserInputException('password', 'notSecure');
		}
	}
	
	/**
	 * @see	wcf\acp\form\UserAddForm::validateEmail()
	 */
	protected function validateEmail($email, $confirmEmail) {
		parent::validateEmail($email, $confirmEmail);
		
		if (!UserRegistrationUtil::isValidEmail($email)) {
			throw new UserInputException('email', 'notValid');
		}
	}
	
	/**
	 * @see	wcf\form\IForm::save()
	 */
	public function save() {
		AbstractForm::save();
		
		// get options
		$this->optionHandler->setInRegistration(true);
		$saveOptions = $this->optionHandler->save();
		$this->additionalFields['languageID'] = $this->languageID;
		$this->additionalFields['registrationIpAddress'] = WCF::getSession()->ipAddress;
		
		// generate activation code
		$addDefaultGroups = true;
		if (REGISTER_ACTIVATION_METHOD == 1 || REGISTER_ACTIVATION_METHOD == 2) {
			$activationCode = UserRegistrationUtil::getActivationCode();
			$this->additionalFields['activationCode'] = $activationCode;
			$addDefaultGroups = false;
			$this->groupIDs = UserGroup::getGroupIDsByType(array(UserGroup::EVERYONE, UserGroup::GUESTS));
		}
		
		// create user
		$data = array(
			'data' => array_merge($this->additionalFields, array(
				'username' => $this->username,
				'email' => $this->email,
				'password' => $this->password,
			)),
			'groups' => $this->groupIDs,
			'languages' => $this->visibleLanguages,
			'options' => $saveOptions,
			'addDefaultGroups' => $addDefaultGroups
		);
		$this->objectAction = new UserAction(array(), 'create', $data);
		$result = $this->objectAction->executeAction();
		$user = $result['returnValues'];
		$userEditor = new UserEditor($user);
		
		// update user rank
		if (MODULE_USER_RANK) {
			$action = new UserProfileAction(array($userEditor), 'updateUserRank');
			$action->executeAction();
		}
		// update user online marking
		$action = new UserProfileAction(array($userEditor), 'updateOnlineMarking');
		$action->executeAction();
		
		// update session
		WCF::getSession()->changeUser($user);
		
		// activation management
		if (REGISTER_ACTIVATION_METHOD == 0) {
			$this->message = 'wcf.user.register.success';
		}
		
		if (REGISTER_ACTIVATION_METHOD == 1) {
			$mail = new Mail(array($this->username => $this->email),
				WCF::getLanguage()->getDynamicVariable('wcf.user.register.needActivation.mail.subject'),
				WCF::getLanguage()->getDynamicVariable('wcf.user.register.needActivation.mail', array('user' => $user))
			);
			$mail->send();
			$this->message = 'wcf.user.register.needActivation';
		}

		if (REGISTER_ACTIVATION_METHOD == 2) {
			$this->message = 'wcf.user.register.awaitActivation';
		}
		
		// notify admin
		if (REGISTER_ADMIN_NOTIFICATION) {
			// get default language
			$language = LanguageFactory::getInstance()->getLanguage(LanguageFactory::getInstance()->getDefaultLanguageID());
			
			// send mail
			$mail = new Mail(MAIL_ADMIN_ADDRESS, 
				$language->getDynamicVariable('wcf.user.register.notification.mail.subject'),
				$language->getDynamicVariable('wcf.user.register.notification.mail', array('user' => $user))
			);
			$mail->send();
		}
		
		// login user
		UserAuthenticationFactory::getUserAuthentication()->storeAccessData($user, $this->username, $this->password);
		WCF::getSession()->unregister('recaptchaDone');
		$this->saved();
		
		// forward to index page
		HeaderUtil::delayedRedirect(LinkHandler::getInstance()->getLink('Index'), WCF::getLanguage()->getDynamicVariable($this->message, array('user' => $user)), 15);
		exit;
	}
}
