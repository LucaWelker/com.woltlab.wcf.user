<?php
namespace wcf\form;
use wcf\data\user\UserAction;
use wcf\system\bbcode\MessageParser;
use wcf\system\menu\user\UserMenu;
use wcf\system\WCF;

/**
 * Shows the signature edit form.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	form
 * @category	Community Framework
 */
class SignatureEditForm extends MessageForm {
	/**
	 * @see wcf\page\AbstractPage::$loginRequired
	 */
	public $loginRequired = true;
	
	/**
	 * @see wcf\page\AbstractPage::$templateName
	 */
	public $templateName = 'signatureEdit';
	
	/**
	 * parsed signature cache
	 * @var	string
	 */
	public $signatureCache = null;
	
	/**
	 * @see	wcf\form\RecaptchaForm::$useCaptcha
	 */
	public $useCaptacha = false;
	
	/**
	 * @see	wcf\form\MessageForm::$permissionCanUseSmilies
	 */
	public $permissionCanUseSmilies = 'user.community.signature.canUseSmilies';
	
	/**
	 * @see	wcf\form\MessageForm::$permissionCanUseHtml
	 */
	public $permissionCanUseHtml = 'user.community.signature.canUseHtml';
	
	/**
	 * @see	wcf\form\MessageForm::$permissionCanUseBBCodes
	 */
	public $permissionCanUseBBCodes = 'user.community.signature.canUseBBCodes';
	
	/**
	 * @see wcf\form\IForm::validate()
	 */
	public function validate() {
		AbstractForm::validate();
		
		$this->validateText();
	}
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		// default values
		if (empty($_POST)) {
			$this->enableBBCodes = WCF::getUser()->signatureEnableBBCodes;
			$this->enableHtml = WCF::getUser()->signatureEnableHtml;
			$this->enableSmilies = WCF::getUser()->signatureEnableSmilies;
			$this->parseURL = true;
		}
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'signatureCache' => $this->signatureCache
		));
	}
	
	/**
	 * @see wcf\page\IPage::show()
	 */
	public function show() {
		// set active tab
		UserMenu::getInstance()->setActiveMenuItem('wcf.user.menu.profile.signature');
		
		// get signature
		if ($this->signatureCache == null) $this->signatureCache = WCF::getUser()->signatureCache;
		$this->text = WCF::getUser()->signature;
		
		parent::show();
	}
	
	/**
	 * @see wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
		
		$this->signatureCache = MessageParser::getInstance()->parse($this->text, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes, false);
		$this->objectAction = new UserAction(array(WCF::getUser()), 'update', array(
			'data' => array(
				'signature' => $this->text,
				'signatureCache' => $this->signatureCache,
				'signatureEnableBBCodes' => $this->enableBBCodes,
				'signatureEnableHtml' => $this->enableHtml,
				'signatureEnableSmilies' => $this->enableSmilies
			)
		));
		$this->objectAction->executeAction();
		
		$this->saved();
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
}
