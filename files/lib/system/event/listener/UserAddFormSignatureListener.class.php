<?php
namespace wcf\system\event\listener;
use wcf\system\event\IEventListener;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Handles the user signature in user administration.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.event.listener
 * @category	Community Framework
 */
class UserAddFormSignatureListener implements IEventListener {
	/**
	 * instance of UserAddForm
	 * @var	wcf\acp\form\UserAddForm
	 */
	protected $eventObj = null;
	
	/**
	 * signature text
	 * @var string
	 */
	public $signature = '';
	
	/**
	 * enables smilies
	 * @var boolean
	 */
	public $signatureEnableSmilies = 1;
	
	/**
	 * enables bbcodes
	 * @var boolean
	 */
	public $signatureEnableBBCodes = 1;
	
	/**
	 * enables html
	 * @var boolean
	 */
	public $signatureEnableHtml = 0;
	
	/**
	 * true to disable this signature
	 * @var boolean
	 */
	public $disableSignature = 0;
	
	/**
	 * reason
	 * @var string
	 */
	public $disableSignatureReason = '';
	
	/**
	 * @see	wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (!MODULE_USER_SIGNATURE) return;
		
		$this->eventObj = $eventObj;
		$this->$eventName();
	}
	
	/**
	 * Handles the assignVariables event.
	 */
	protected function assignVariables() {
		WCF::getTPL()->assign(array(
			'signature' => $this->signature,
			'signatureEnableBBCodes' => $this->signatureEnableBBCodes,
			'signatureEnableSmilies' => $this->signatureEnableSmilies,
			'signatureEnableHtml' => $this->signatureEnableHtml,
			'disableSignature' => $this->disableSignature,
			'disableSignatureReason' => $this->disableSignatureReason
		));
	}
	
	/**
	 * Handles the readData event.
	 * This is only called in UserEditForm.
	 */
	protected function readData() {
		if (empty($_POST)) {
			$this->signature = $this->eventObj->user->signature;
			$this->signatureEnableBBCodes = $this->eventObj->user->signatureEnableBBCodes;
			$this->signatureEnableSmilies = $this->eventObj->user->signatureEnableSmilies;
			$this->signatureEnableHtml = $this->eventObj->user->signatureEnableHtml;
			$this->disableSignature = $this->eventObj->user->disableSignature;
			$this->disableSignatureReason = $this->eventObj->user->disableSignatureReason;
		}
	}
	
	/**
	 * Handles the readFormParameters event.
	 */
	protected function readFormParameters() {
		if (isset($_POST['signature'])) $this->signature = StringUtil::trim($_POST['signature']);
		if (isset($_POST['disableSignatureReason'])) $this->disableSignatureReason = StringUtil::trim($_POST['disableSignatureReason']);
		
		$this->signatureEnableBBCodes = $this->signatureEnableSmilies = 0;
		if (!empty($_POST['signatureEnableBBCodes'])) $this->signatureEnableBBCodes = 1;
		if (!empty($_POST['signatureEnableSmilies'])) $this->signatureEnableSmilies = 1;
		if (!empty($_POST['signatureEnableHtml'])) $this->signatureEnableHtml = 1;
		if (!empty($_POST['disableSignature'])) $this->disableSignature = 1;
	}
	
	/**
	 * Handles the save event.
	 */
	protected function save() {
		$this->eventObj->additionalFields = array_merge($this->eventObj->additionalFields, array(
			'signature' => $this->signature,
			'signatureEnableBBCodes' => $this->signatureEnableBBCodes,
			'signatureEnableSmilies' => $this->signatureEnableSmilies,
			'signatureEnableHtml' => $this->signatureEnableHtml,
			'disableSignature' => $this->disableSignature,
			'disableSignatureReason' => $this->disableSignatureReason
		));
	}
}
