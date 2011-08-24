<?php
namespace wcf\form;
use wcf\data\user\User;
use wcf\data\user\UserEditor;
use wcf\form\AbstractForm;
use wcf\system\exception\NamedUserException;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\UserUtil;

/**
 * Shows the email activation form.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	form
 * @category 	Community Framework
 */
class EmailActivationForm extends RegisterActivationForm {
	// system
	public $templateName = 'emailActivation';
	
	/**
	 * @see wcf\form\Form::validate()
	 */
	public function validate() {
		AbstractForm::validate();
		
		// check given user id
		$this->user = new UserEditor(new User($this->userID));
		if (!$this->user->userID) {
			throw new UserInputException('u', 'notValid');
		}
		
		// user is already enabled
		if ($this->user->reactivationCode == 0) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.user.emailChange.error.emailAlreadyEnabled'));
		}
		
		// check whether the new email isn't unique anymore
		if (!UserUtil::isAvailableEmail($this->user->newEmail)) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.user.emailChange.error.email.notUnique'));
		}
		
		// check given activation code
		if ($this->user->reactivationCode != $this->activationCode) {
			throw new UserInputException('a', 'notValid');
		}
	}
	
	/**
	 * @see wcf\form\Form::save()
	 */
	public function save() {
		AbstractForm::save();
		
		// enable new email
		$this->user->update(array(
			'email' => $this->user->newEmail,
			'newEmail' => '',
			'reactivationCode' => 0
		));
		
		// reset session
		$this->saved();
		
		// forward to index page
		WCF::getTPL()->assign(array(
			'url' => 'index.php'.SID_ARG_1ST,
			'message' => WCF::getLanguage()->get('wcf.user.emailChange.reactivation.success')
		));
		WCF::getTPL()->display('redirect');
		exit;
	}
}
