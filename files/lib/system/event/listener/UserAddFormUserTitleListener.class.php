<?php
namespace wcf\system\event\listener;
use wcf\system\event\IEventListener;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Handles the user title in user administration.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.event.listener
 * @category	Community Framework
 */
class UserAddFormUserTitleListener implements IEventListener {
	/**
	 * instance of UserAddForm
	 * @var wcf\acp\form\UserAddForm
	 */
	protected $eventObj = null;
	
	/**
	 * user title
	 * @var string
	 */
	protected $priority = 0;
	
	/**
	 * @see	\wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (!MODULE_USER_RANK) return;
		
		$this->eventObj = $eventObj;
		$this->$eventName();
	}
	
	/**
	 * Handles the assignVariables event.
	 */
	protected function assignVariables() {
		WCF::getTPL()->assign(array(
			'userTitle' => $this->userTitle
		));
	}
	
	/**
	 * Handles the readData event.
	 * This is only called in UserEditForm.
	 */
	protected function readData() {
		if (empty($_POST)) {
			$this->userTitle = $this->eventObj->user->userTitle;
		}
	}
	
	/**
	 * Handles the readFormParameters event.
	 */
	protected function readFormParameters() {
		if (isset($_POST['userTitle'])) $this->userTitle = intval($_POST['userTitle']);
	}
	
	/**
	 * Handles the save event.
	 */
	protected function save() {
		$this->eventObj->additionalFields = array_merge($this->eventObj->additionalFields, array(
			'userTitle' => $this->userTitle
		));
	}
	
	/**
	 * Handles the validate event.
	 */
	protected function validate() {
		try {
			if (StringUtil::length($this->userTitle) > USER_TITLE_MAX_LENGTH) {
				throw new UserInputException('userTitle', 'tooLong');
			}
			if (!StringUtil::executeWordFilter($this->userTitle, USER_FORBIDDEN_TITLES)) {
				throw new UserInputException('userTitle', 'forbidden');
			}
		}
		catch (UserInputException $e) {
			$this->eventObj->errorType[$e->getField()] = $e->getType();
		}
	}
}
