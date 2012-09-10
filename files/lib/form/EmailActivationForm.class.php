<?php
namespace wcf\form;
use wcf\data\user\User;
use wcf\data\user\UserEditor;
use wcf\form\AbstractForm;
use wcf\system\exception\NamedUserException;
use wcf\system\exception\UserInputException;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\HeaderUtil;
use wcf\util\UserUtil;

/**
 * Shows the email activation form.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	form
 * @category 	Community Framework
 */
class EmailActivationForm extends RegisterActivationForm {
	/**
	 * @see	wcf\form\IForm::validate()
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
			throw new NamedUserException(WCF::getLanguage()->get('wcf.user.emailActivation.error.emailAlreadyEnabled'));
		}
		
		// check whether the new email isn't unique anymore
		if (!UserUtil::isAvailableEmail($this->user->newEmail)) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.user.email.error.notUnique'));
		}
		
		// check given activation code
		if ($this->user->reactivationCode != $this->activationCode) {
			throw new UserInputException('a', 'notValid');
		}
	}
	
	/**
	 * @see	wcf\form\IForm::save()
	 */
	public function save() {
		AbstractForm::save();
		
		// enable new email
		$this->user->update(array(
			'email' => $this->user->newEmail,
			'newEmail' => '',
			'reactivationCode' => 0
		));
		$this->saved();
		
		// forward to index page
		HeaderUtil::delayedRedirect(LinkHandler::getInstance()->getLink('Index'), WCF::getLanguage()->get('wcf.user.emailActivation.success'));
		exit;
	}
}
