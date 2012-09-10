<?php
namespace wcf\form;
use wcf\data\user\group\UserGroup;
use wcf\data\user\User;
use wcf\data\user\UserAction;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\NamedUserException;
use wcf\system\exception\UserInputException;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\HeaderUtil;

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
	 * @var	integer
	 */
	public $userID = null;
	
	/**
	 * activation code
	 * @var	integer
	 */
	public $activationCode = '';
	
	/**
	 * User object
	 * @var	wcf\data\user\User
	 */
	public $user = null;
	
	/**
	 * @see	wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_GET['u']) && !empty($_GET['u'])) $this->userID = intval($_GET['u']);
		if (isset($_GET['a']) && !empty($_GET['a'])) $this->activationCode = intval($_GET['a']);
	}
	
	/**
	 * @see	wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['u']) && !empty($_POST['u'])) $this->userID = intval($_POST['u']);
		if (isset($_POST['a']) && !empty($_POST['a'])) $this->activationCode = intval($_POST['a']);
	}
	
	/**
	 * @see	wcf\form\IForm::validate()
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
			throw new NamedUserException(WCF::getLanguage()->get('wcf.user.registerActivation.error.userAlreadyEnabled'));
		}
		
		// check given activation code
		if ($this->user->activationCode != $this->activationCode) {
			throw new UserInputException('a', 'notValid');
		}
	}
	
	/**
	 * @see	wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
		
		// enable user
		// update activation code
		$this->objectAction = new UserAction(array($this->user->userID), 'update', array(
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
		$this->objectAction->executeAction();
		$this->saved();
		
		// forward to index page
		HeaderUtil::delayedRedirect(LinkHandler::getInstance()->getLink('Index'), WCF::getLanguage()->get('wcf.user.registerActivation.success'), 10);
		exit;
	}
	
	/**
	 * @see	wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'u' => $this->userID,
			'a' => $this->activationCode
		));
	}
	
	/**
	 * @see	wcf\page\IPage::show()
	 */
	public function show() {
		if (REGISTER_ACTIVATION_METHOD != 1) {
			throw new IllegalLinkException();
		}
		
		if (empty($_POST) && $this->userID !== null && $this->activationCode != 0) {
			$this->submit();
		}
		
		parent::show();
	}
}
