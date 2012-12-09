<?php
namespace wcf\system\event\listener;
use wcf\system\event\IEventListener;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Handles additional settings in user group administration.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	WoltLab Burning Board License <http://www.woltlab.com/products/burning_board/license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.event.listener
 * @category	Community Framework
 */
class UserGroupAddFormAdditionalSettingsListener implements IEventListener {
	/**
	 * instance of UserGroupAddForm
	 * @var wcf\acp\form\UserGroupAddForm
	 */
	protected $eventObj = null;
	
	/**
	 * group priority
	 * @var integer
	 */
	protected $priority = 0;
	
	/**
	 * user online marking string
	 * @var string
	 */
	protected $userOnlineMarking = '%s';
	
	/**
	 * shows the members of this group on the team page
	 * @var unknown_type
	 */
	protected $showOnTeamPage = 0;
	
	/**
	 * @see	\wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		$this->eventObj = $eventObj;
		
		$this->$eventName();
	}
	
	/**
	 * Handles the assignVariables event.
	 */
	protected function assignVariables() {
		WCF::getTPL()->assign(array(
			'priority' => $this->priority,
			'userOnlineMarking' => $this->userOnlineMarking,
			'showOnTeamPage' => $this->showOnTeamPage
		));
	}
	
	/**
	 * Handles the readData event.
	 * This is only called in UserGroupEditForm.
	 */
	protected function readData() {
		if (empty($_POST)) {
			$this->priority = $this->eventObj->group->priority;
			$this->userOnlineMarking = $this->eventObj->group->userOnlineMarking;
			$this->showOnTeamPage = $this->eventObj->group->showOnTeamPage;
		}
	}
	
	/**
	 * Handles the readFormParameters event.
	 */
	protected function readFormParameters() {
		if (isset($_POST['priority'])) $this->priority = intval($_POST['priority']);
		if (isset($_POST['userOnlineMarking'])) $this->userOnlineMarking = StringUtil::trim($_POST['userOnlineMarking']);
		if (isset($_POST['showOnTeamPage'])) $this->showOnTeamPage = intval($_POST['showOnTeamPage']);
	}
	
	/**
	 * Handles the save event.
	 */
	protected function save() {
		$this->eventObj->additionalFields = array_merge($this->eventObj->additionalFields, array(
			'priority' => $this->priority,
			'userOnlineMarking' => $this->userOnlineMarking,
			'showOnTeamPage' => $this->showOnTeamPage
		));
	}
}
