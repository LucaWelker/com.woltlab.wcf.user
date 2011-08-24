<?php
namespace wcf\form;
use wcf\data\user\User;
use wcf\data\user\UserEditor;
use wcf\system\exception\UserInputException;
use wcf\system\mail\Mail;
use wcf\system\WCF;
use wcf\util\UserRegistrationUtil;

/**
 * Shows the new email activation code form.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	form
 * @category 	Community Framework
 */
class EmailNewActivationCodeForm extends RegisterNewActivationCodeForm {
	// system
	public $templateName = 'emailNewActivationCode';
	
	/**
	 * @see wcf\page\Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (WCF::getUser()->userID) {
			$this->username = WCF::getUser()->username;
		}
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
		
		if ($this->user->reactivationCode == 0) {
			throw new UserInputException('username', 'alreadyEnabled');
		}
	}
	
	/**
	 * @see wcf\form\Form::save()
	 */
	public function save() {
		AbstractForm::save();
		
		// generate activation code
		$activationCode = UserRegistrationUtil::getActivationCode();
		
		$fields = array('reactivationCode' => $activationCode);
		if (!empty($this->email)) $fields['newEmail'] = $this->email;
		
		// save user
		$userEditor = new UserEditor($this->user);
		$userEditor->update($fields);
		
		// send activation mail
		$subjectData = array('PAGE_TITLE' => WCF::getLanguage()->get(PAGE_TITLE));
		$messageData = array(
			'PAGE_TITLE' => WCF::getLanguage()->get(PAGE_TITLE),
			'$username' => $this->user->username,
			'$userID' => $this->user->userID,
			'$activationCode' => $activationCode,
			'PAGE_URL' => PAGE_URL,
			'MAIL_ADMIN_ADDRESS' => MAIL_ADMIN_ADDRESS
		);
		$mail = new Mail(array($this->user->username => !empty($this->email) ? $this->email : $this->user->email), WCF::getLanguage()->get('wcf.user.emailChange.needReactivation.mail.subject', $subjectData), WCF::getLanguage()->get('wcf.user.emailChange.needReactivation.mail', $messageData));
		$mail->send();
		$this->saved();
		
		// forward to index page
		WCF::getTPL()->assign(array(
			'url' => 'index.php'.SID_ARG_1ST,
			'message' => WCF::getLanguage()->get('wcf.user.emailChange.needReactivation')
		));
		WCF::getTPL()->display('redirect');
		exit;
	}
}
