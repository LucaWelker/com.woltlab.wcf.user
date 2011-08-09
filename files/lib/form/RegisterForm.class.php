<?php
namespace wcf\form;
use wcf\acp\form\UserAddForm;
use wcf\data\option\Option;
use wcf\data\language\Language;
use wcf\data\user\group\UserGroup;
use wcf\data\user\UserAction;
use wcf\system\auth\UserAuth;
use wcf\system\exception\NamedUserException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\language\LanguageFactory;
use wcf\system\mail\Mail;
use wcf\system\recaptcha\RecaptchaHandler;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
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
 * @category 	Community Framework
 */
class RegisterForm extends UserAddForm {
	/**
	 * @see wcf\page\AbstractPage::$templateName
	 */
	public $templateName = 'register';
	
	/**
	 * @see wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array();
	
	/**
	 * holds a language variable with information about the registration process
	 * e.g. if you need to activate your account
	 * @var string
	 */
	public $message = '';
	
	/**
	 * challenge
	 * @var	string
	 */	
	public $challenge = '';
	
	/**
	 * response
	 * @var	string
	 */	
	public $response = '';
	
	/**
	 * enable recaptcha
	 * @var	boolean
	 */
	public $useCaptcha = true;
	
	/**
	 * @see wcf\page\IPage::readParameters()
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
		
		if (REGISTER_USE_CAPTCHA || WCF::getSession()->getVar('captchaDone')) {
			$this->useCaptcha = false;
		}
	}
	
	/**
	 * @see wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		$this->groupIDs = array();
		if (isset($_POST['recaptcha_challenge_field'])) $this->challenge = StringUtil::trim($_POST['recaptcha_challenge_field']);
		if (isset($_POST['recaptcha_response_field'])) $this->response = StringUtil::trim($_POST['recaptcha_response_field']);
	}
	
	/**
	 * @see wcf\form\IForm::validate()
	 */
	public function validate() {
		// validate captcha first
		$this->validateCaptcha();
		
		parent::validate();
	}
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->languageID = WCF::getLanguage()->languageID;
		}
		
		$this->options = $this->getOptionTree('profile');
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		RecaptchaHandler::getInstance()->assignVariables();
		WCF::getTPL()->assign(array(
			'username' => $this->username,
			'email'	=> $this->email,
			'confirmEmail' => $this->confirmEmail,
			'password' => $this->password,
			'confirmPassword' => $this->confirmPassword,
			'optionCategories' => $this->options,
			'availableLanguages' => $this->getAvailableLanguages(),
			'languageID' => $this->languageID,
			'visibleLanguages' => $this->visibleLanguages,
			'availableContentLanguages' => $this->getAvailableContentLanguages()
		));
	}
	
	/**
	 * @see Form::show()
	 */
	public function show() {
		// get user options and categories from cache
		$this->readCache();
		
		AbstractForm::show();
	}

	/**
	 * @see wcf\acp\form\AbstractOptionListForm::checkOption()
	 */
	protected static function checkOption(Option $option) {
		// show only enabled and required options
		if ($option->disabled || (!$option->required && !$option->askDuringRegistration && $option->editable != 2)) return false;

		// show options editable by user 
		return ($option->editable <= 2);
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
	 * @see wcf\acp\form\UserAddForm::validateUsername()
	 */
	protected function validateUsername($username) {
		parent::validateUsername($username);
		
		// check for min-max length
		if (!UserRegistrationUtil::isValidUsername($username)) {
			throw new UserInputException('username', 'notValid');
		}
	}
	
	/**
	 * @see wcf\acp\form\UserAddForm::validatePassword()
	 */
	protected function validatePassword($password, $confirmPassword) {
		parent::validatePassword($password, $confirmPassword);
		
		// check security of the given password
		if (!UserRegistrationUtil::isSecurePassword($password)) {
			throw new UserInputException('password', 'notSecure');
		}
	}
	
	/**
	 * @see wcf\acp\form\UserAddForm::validateEmail()
	 */
	protected function validateEmail($email, $confirmEmail) {
		parent::validateEmail($email, $confirmEmail);
		
		if (!UserRegistrationUtil::isValidEmail($email)) {
			throw new UserInputException('email', 'notValid');
		}
	}
	
	/**
	 * @see wcf\form\IForm::save()
	 */
	public function save() {
		AbstractForm::save();
		
		// get options
		$saveOptions = array();
		foreach ($this->options as $option) {
			$saveOptions[$option->optionID] = $this->optionValues[$option->optionName];
		}
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
		$userAction = new UserAction(array(), 'create', $data);
		$result = $userAction->executeAction();
		$user = $result['returnValues'];
		
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
			$language = LanguageFactory::getLanguage(LanguageFactory::getDefaultLanguageID());
			
			// send mail
			$mail = new Mail(MAIL_ADMIN_ADDRESS, 
				$language->getDynamicVariable('wcf.user.register.notification.mail.subject'),
				$language->getDynamicVariable('wcf.user.register.notification.mail', array('user' => $user))
			);
			$mail->send();
		}
		
		// login user
		UserAuth::getInstance()->storeAccessData($user, $this->username, $this->password);
		$this->saved();
		
		// forward to index page
		WCF::getTPL()->assign(array(
			'url' => LinkHandler::getInstance()->getLink('index.php'),
			'message' => $this->message,
			'user' => $user
		));
		WCF::getTPL()->display('redirect');
		exit;
	}
}
