<?php
namespace wcf\form;
use wcf\data\user\User;
use wcf\data\user\UserEditor;
use wcf\system\exception\UserInputException;
use wcf\system\mail\Mail;
use wcf\system\WCF;
use wcf\util\StringUtil;
use wcf\util\UserRegistrationUtil;

/**
 * Shows the new password form.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	form
 * @category 	Community Framework
 */
class NewPasswordForm extends AbstractForm {
	// system
	public $templateName = 'newPassword';
	
	/**
	 * user id
	 * @var	integer
	 */	
	public $userID = 0;
	
	/**
	 * lost password key
	 * @var	string
	 */	
	public $lostPasswordKey = '';
	
	/**
	 * User object
	 * @var	wcf\data\user\User
	 */
	public $user;
	
	/**
	 * new password
	 * @var	string
	 */	
	public $newPassword = '';
	
	
	/**
	 * @see wcf\page\Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['u'])) $this->userID = intval($_REQUEST['u']);
		if (isset($_REQUEST['k'])) $this->lostPasswordKey = StringUtil::trim($_REQUEST['k']);
	}
	
	/**
	 * @see wcf\form\Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		// get user
		$this->user = new User($this->userID);
		
		if (!$this->user->userID) {
			throw new UserInputException('userID', 'invalid');
		}
		if (!$this->user->lostPasswordKey) {
			throw new UserInputException('lostPasswordKey');
		}
		
		if ($this->user->lostPasswordKey != $this->lostPasswordKey) {
			throw new UserInputException('lostPasswordKey', 'invalid');
		}
	}
	
	/**
	 * @see wcf\form\Form::save()
	 */
	public function save() {
		parent::save();
		
		// generate new password
		$this->newPassword = UserRegistrationUtil::getNewPassword((REGISTER_PASSWORD_MIN_LENGTH > 9 ? REGISTER_PASSWORD_MIN_LENGTH : 9));
		
		// update user
		$userEditor = new UserEditor($this->user);
		$userEditor->update(array(
			'password' => $this->newPassword,
			'lastLostPasswordRequest' => 0,
			'lostPasswordKey' => ''
		));
		
		// send mail
		$subjectData = array('PAGE_TITLE' => WCF::getLanguage()->get(PAGE_TITLE));
		$messageData = array(
			'PAGE_TITLE' => WCF::getLanguage()->get(PAGE_TITLE),
			'$username' => $this->user->username,
			'$userID' => $this->user->userID,
			'$newPassword' => $this->newPassword,
			'PAGE_URL' => PAGE_URL,
			'MAIL_ADMIN_ADDRESS' => MAIL_ADMIN_ADDRESS
		);
		$mail = new Mail(array($this->user->username => $this->user->email), WCF::getLanguage()->get('wcf.user.lostPassword.newPassword.mail.subject', $subjectData), WCF::getLanguage()->get('wcf.user.lostPassword.newPassword.mail', $messageData));
		$mail->send();
		$this->saved();
		
		// show result page
		WCF::getTPL()->assign(array(
			'url' => 'index.php'.SID_ARG_1ST,
			'message' => WCF::getLanguage()->get('wcf.user.lostPassword.success')
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
			'userID' => $this->userID,
			'lostPasswordKey' => $this->lostPasswordKey
		));
	}
	
	/**
	 * @see wcf\page\Page::readData()
	 */
	public function readData() {
		AbstractPage::readData();
		
		if (count($_POST) || (!empty($this->userID) && !empty($this->lostPasswordKey))) {
			$this->submit();
		}
	}
}
