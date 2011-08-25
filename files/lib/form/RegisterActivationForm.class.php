<?php
namespace wcf\form;
use wcf\data\user\group\UserGroup;
use wcf\data\user\User;
use wcf\data\user\UserAction;
use wcf\system\exception\UserInputException;
use wcf\system\exception\NamedUserException;
use wcf\system\WCF;

/**
 * Shows the user activation form.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	form
 * @category 	Community Framework
 */
class RegisterActivationForm extends AbstractForm {
	/**
	 * user id
	 * @var integer
	 */
	public $userID = null;
	
	/**
	 * activation code
	 * @var integer
	 */
	public $activationCode = '';
	
	/**
	 * User object
	 * @var	wcf\data\user\User
	 */
	public $user = null;
	
	/**
	 * @see wcf\page\AbstractPage::$templateName
	 */
	public $templateName = 'registerActivation';
	
	/**
	 * @see wcf\page\Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_GET['u']) && !empty($_GET['u'])) $this->userID = intval($_GET['u']);
		if (isset($_GET['a']) && !empty($_GET['a'])) $this->activationCode = intval($_GET['a']);
	}
	
	/**
	 * @see wcf\form\Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['u']) && !empty($_POST['u'])) $this->userID = intval($_POST['u']);
		if (isset($_POST['a']) && !empty($_POST['a'])) $this->activationCode = intval($_POST['a']);
	}
	
	/**
	 * @see wcf\form\Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		// check given user id
		$this->user = new User($this->userID);
		if (!$this->user->userID) {
			throw new UserInputException('u', 'notValid');
		}
		
		// user is already enabled
		if ($this->user->activationCode == 0) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.user.register.error.userAlreadyEnabled'));
		}
		
		// check given activation code
		if ($this->user->activationCode != $this->activationCode) {
			throw new UserInputException('a', 'notValid');
		}
	}
	
	/**
	 * @see wcf\form\Form::save()
	 */
	public function save() {
		parent::save();

		// enable user
		// update activation code
		$userAction = new UserAction(array($this->user->userID), 'update', array(
			'data' => array(
				'activationCode' => 0
			),
			'groups' => array(
				UserGroup::USERS
			),
			'removeGroups' => array(
				UserGroup::GUESTS
			)
		));
		$userAction->executeAction();
		
		$this->saved();
		
		// forward to login page
		WCF::getTPL()->assign(array(
			'url' => 'index.php'.SID_ARG_1ST,
			'message' => WCF::getLanguage()->get('wcf.user.register.activation.redirect')
		));
		WCF::getTPL()->display('redirect');
		exit;
	}
	
	/**
	 * @see wcf\page\Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'u' => $this->userID,
			'a' => $this->activationCode
		));
	}
	
	/**
	 * @see wcf\page\Page::show()
	 */
	public function show() {
		if (!count($_POST) && $this->userID !== null && $this->activationCode != 0) {
			$this->submit();
		}
		
		parent::show();
	}
}
